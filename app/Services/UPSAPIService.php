<?php

namespace App\Services;

use App\Models\CarrierService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Throwable;

class UPSAPIService
{
    public function shippingRates($origin, $destination, $weight, $currency, $locale)
    {
        try {
            // Obtain access token
            $token = $this->getAccessToken();

            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token->access_token,
                'transactionSrc' => 'rate-shopper',
            ];

            // Adjust weight and unit if necessary
            $weightUnit = ($origin['country'] === 'US') ? 'LBS' : 'KGS';
            $weightToSend = ($weightUnit === 'LBS') ? $weight / 453.592 : $weight;

            $body = [
                "originCountryCode" => $origin['country'],
                "originStateProvince" => $origin['province'] ?? '',
                "originCityName" => $origin['city'],
                "originPostalCode" => $origin['postal_code'],
                "destinationCountryCode" => $destination['country'],
                "destinationStateProvince" => $destination['province'] ?? '',
                "destinationCityName" => $destination['city'],
                "destinationPostalCode" => $destination['postal_code'],
                "weight" => number_format($weightToSend, 2, '.', ''),
                "weightUnitOfMeasure" => $weightUnit,
                "shipmentContentsValue" => "100.00",
                "shipmentContentsCurrencyCode" => $currency,
                "billType" => "03",
                "shipDate" => date('Y-m-d'),
                "shipTime" => date('H:i'),
                "avvFlag" => true,
                "numberOfPackages" => "1"
            ];

            $request = new Request('POST', env('UPS_LIVE_URL') . '/api/shipments/v1/transittimes', $headers, json_encode($body));
            $response = $client->send($request);
            $responseBody = json_decode($response->getBody(), true);

            if (!isset($responseBody['emsResponse']['services'])) {
                return response()->json(['error' => 'Services not found in the response'], 400);
            }

            $services = $responseBody['emsResponse']['services'];
            $rates = [];
            $specialServices = [];

            // Add special services for UPS SurePost if applicable
            if ($origin['country'] === 'US' && $destination['country'] === 'US') {
                $weightInPounds = $weight / 453.592;
                $specialServices[] = [
                    'serviceLevel' => $weightInPounds < 1 ? '92' : '93',
                    'serviceLevelDescription' => $weightInPounds < 1
                        ? 'UPS SurePost Less than 1 lb'
                        : 'UPS SurePost 1 lb or greater',
                    'businessTransitDays' => null,
                    'deliveryDate' => null,
                    'deliveryTime' => null,
                ];
            }


            $services = array_merge($specialServices, $services);
            $filtered_services = $this->FilterServices($services);

            $serviceCodes = CarrierService::where('carrier_name', 'UPS')->pluck('code', 'service_name')->toArray();
            $allowedServices = CarrierService::where('carrier_name', 'UPS')
                ->where('api_allowed', 1)
                ->pluck('code')
                ->toArray();

            $all_codes = $this->getCodes($filtered_services, $serviceCodes, $allowedServices);

            foreach ($all_codes as $service) {


                if ($service !== false || stripos($service['serviceLevelDescription'], 'surepost') !== false) {

                    $shippingData = $this->shippingCost($origin, $destination, $weight, $currency, $token->access_token, $service);

                    if (!empty($shippingData)) {
                        $rates[] = [
                            'service_name' => $service['serviceLevelDescription'],
                            'service_code' => $shippingData['service_code'],
                            'total_price' => $shippingData['total_price'] ?? 'N/A',
                            'currency' => $currency,
                            'min_delivery_date' => $service['deliveryDate'] ?? 'N/A',
                            'max_delivery_date' => $service['deliveryTime'] ?? 'N/A',
                        ];
                    }

                    // if (!empty($shippingData)) {
                    //     $rates[] = [
                    //         'service_name' => $serviceDescription,
                    //         'service_code' => $shippingData['service_code'],
                    //         'total_price' => $shippingData['total_price'] ?? 'N/A',
                    //         'currency' => $currency,
                    //         'min_delivery_date' => $service['deliveryDate'] ?? 'N/A',
                    //         'max_delivery_date' => $service['deliveryTime'] ?? 'N/A',
                    //     ];
                    // }
                }
            }

            return $rates;

        } catch (Throwable $th) {

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getAccessToken()
    {
        // Create a Guzzle HTTP client instance
        $client = new Client();

        // Prepare the request options
        $options = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-merchant-id' => env('UPS_MERCHANT_ID'), // Replace with your actual merchant ID or remove if not required
                'Authorization' => 'Basic ' . base64_encode(env('UPS_CLIENT_ID') . ':' . env('UPS_CLIENT_SECRET')),
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ];


        try {
            // Send the POST request to the OAuth token endpoint
            $response = $client->post(env('UPS_LIVE_URL') . '/security/v1/oauth/token', $options);
            $body = $response->getBody()->getContents();
            return json_decode($body);
        } catch (RequestException $ex) {
            echo 'Error: ' . $ex->getMessage();
            return null; // Or handle the error accordingly
        }
    }

    public function FilterServices($services)
    {
        $cleanedServices = [];
        foreach ($services as $service) {
            // Remove the trademark symbol `®` and full stop `.` from the description
            $cleanedDescription = str_replace(['.', '®'], '', $service['serviceLevelDescription']);

            // If this description is already in the cleanedServices array, skip adding it again
            if (isset($cleanedServices[$cleanedDescription])) {
                continue;
            }

            // Add cleaned description back to service
            $service['serviceLevelDescription'] = $cleanedDescription;

            // Store the service in the cleanedServices array using the cleaned description as the key
            $cleanedServices[$cleanedDescription] = $service;
        }
        return $cleanedServices;
    }

    public function getCodes($services, $service_codes, $allowedServices)
    {
        $filteredServices = [];

        // Iterate through the services to assign codes
        foreach ($services as $service) {
            $description = $service['serviceLevelDescription'];

            // Check if the service description matches a service code and is allowed
            if (isset($service_codes[$description]) && in_array($service_codes[$description], $allowedServices)) {
                // Add to filteredServices only if there's a matching service code and it's allowed
                $filteredServices[] = [
                    'serviceLevelDescription' => $description,
                    'serviceCode' => $service_codes[$description],
                ];
            }
        }

        return $filteredServices;
    }

    public function shippingCost($origin, $destination, $weight, $currency, $token, $service)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ];

            $weightInOunces = max($weight * 0.035274, 0.1);


            $body = [
                "RateRequest" => [
                    "Request" => [
                        "TransactionReference" => [
                            "CustomerContext" => "CustomerContext"
                        ]
                    ],
                    "Shipment" => [
                        "Shipper" => [
                            "Name" => "ShipperName",
                            "ShipperNumber" => env("UPS_SHIPPER_NUMBER"),
                            "Address" => [
                                "AddressLine" => [
                                    $origin['address1']
                                ],
                                "City" => $origin['city'],
                                "StateProvinceCode" => $origin['province'],
                                "PostalCode" => $origin['postal_code'],
                                "CountryCode" => $origin['country']
                            ]
                        ],
                        "ShipTo" => [
                            "Name" => "ShipToName",
                            "Address" => [
                                "AddressLine" => [
                                    $destination['address1']
                                ],
                                "City" => $destination['city'],
                                "StateProvinceCode" => $destination['province'],
                                "PostalCode" => $destination['postal_code'],
                                "CountryCode" => $destination['country']
                            ]
                        ],
                        "ShipFrom" => [
                            "Name" => "ShipFromName",
                            "Address" => [
                                "AddressLine" => [
                                    $origin['address1']
                                ],
                                "City" => $origin['city'],
                                "StateProvinceCode" => $origin['province'],
                                "PostalCode" => $origin['postal_code'],
                                "CountryCode" => $origin['country']
                            ]
                        ],
                        "PaymentDetails" => [
                            "ShipmentCharge" => [
                                "Type" => "01",
                                "BillShipper" => [
                                    "AccountNumber" => env("UPS_SHIPPER_NUMBER")
                                ]
                            ]
                        ],
                        "ShipmentRatingOptions" => [
                            "NegotiatedRatesIndicator" => "Y"
                        ],
                        "Service" => [
                            "Code" => (string)$service['serviceCode'],
                        ],
                        "NumOfPieces" => "1",
                        "Package" => [
                            "PackagingType" => [
                                "Code" => "2",
                            ],
                            "Dimensions" => [
                                "UnitOfMeasurement" => [
                                    "Code" => "IN",
                                    "Description" => "Inches"
                                ],
                                "Length" => "5",
                                "Width" => "5",
                                "Height" => "5"
                            ],
                            "PackageWeight" => [
                                "UnitOfMeasurement" => [
                                    "Code" => $service['serviceCode'] === '92' ? 'OZS' : 'LBS',
                                ],
                                "Weight" => number_format($weightInOunces / 16, 1, '.', '')
                            ]
                        ]
                    ]
                ]
            ];

            $request = new Request('POST', env('UPS_LIVE_URL') . '/api/rating/v2403/Rate', $headers, json_encode($body));
            $response = $client->send($request);
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['RateResponse']['Response']['ResponseStatus']['Code'] === "1") {
                $totalPrice = $responseBody['RateResponse']['RatedShipment']['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'] ?? 'N/A';
                $currencyCode = $responseBody['RateResponse']['RatedShipment']['TotalCharges']['CurrencyCode'] ?? 'USD';
                $minDeliveryDate = $responseBody['RateResponse']['RatedShipment']['GuaranteedDelivery']['MinDate'] ?? 'N/A';
                $maxDeliveryDate = $responseBody['RateResponse']['RatedShipment']['GuaranteedDelivery']['MaxDate'] ?? 'N/A';

                return [
                    'service_name' => $service['serviceLevelDescription'],
                    'service_code' => (string)$service['serviceCode'],
                    'total_price' => (float)$totalPrice,
                    'currency' => $currencyCode,
                    'min_delivery_date' => $minDeliveryDate,
                    'max_delivery_date' => $maxDeliveryDate
                ];
            } else {
                return ['error' => 'Rate response error'];
            }

        } catch (Throwable $th) {
            return [];
        }
    }


}
    
    
    
    
    



