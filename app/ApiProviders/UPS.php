<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ApiRequestNote;
use App\Models\CarrierService;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use App\Support\TransitEstimate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Dflydev\DotAccessData\Data;
use function Amp\async;
use Amp\Future;

class UPS implements IApiProvider
{

    /**
     * getApiProviderName
     * Returns the API Provider name as a string
     *
     * @return string
     */
    public function getApiProviderName(): string
    {
        return 'ups';
    }

    /**
     * getCarrierName
     * Returns the carrier name
     *
     * @return string
     */
    public function getCarrierName(): string
    {
        return 'ups';
    }

    /**
     * getRates
     * This method is responsible for calling the carrier api
     * and returning the responses as an array (of decoded responses)
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function getRates(ShippingQuoteHeader $shippingQuote): array
    {
        $promises[] = async(function() use($shippingQuote) {
            return $this->getRatesAlmostAll($shippingQuote);
        });

        if ((strtoupper($shippingQuote->shipTo->countryCode) == 'US') && ($shippingQuote->shipFrom->countryCode == 'US')) {
            $lbsConversion = UnitConversions::grams_to_pounds($shippingQuote->orderWeightWithPackagingInGrams);
            if ($lbsConversion <= 1) {
                $promises[] = async(function() use($shippingQuote) {
                    ApiRequestNote::newNote('debug','UPS eq or lt 1 lbs');

                    return $this->getSurePost($shippingQuote,92);
                });
            } else {
                if ($lbsConversion >= 1 && $lbsConversion <= 10) {
                    $promises[] = async(function() use($shippingQuote) {
                        ApiRequestNote::newNote('debug','UPS eq or gt 1 lbs and eq or lt 10 lbs');

                        return $this->getSurePost($shippingQuote,93);
                    });
                }
            }
        }

        //ApiRequestNote::newNote('debug','Starting UPS async');
        $async = Future\awaitAll($promises);
        ApiRequestNote::newNote('debug','UPS async',$async);

        return $async[1];
    }

    /**
     * getSurePostUnder
     * This method is responsible for calling the carrier api
     * and returning the responses as an array (of decoded responses)
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function getSurePost(ShippingQuoteHeader $shippingQuote, int $code): array {
        $token = $this->getAccessToken();

        if (strlen($token) === 0) {
            throw new \Exception('No UPS token');
        }

        if ($code == 92) {
            //convert to ounces
            $unitCode = 'OZS';
            $unitDescription = 'Ounces';
            $weight = UnitConversions::grams_to_ounces($shippingQuote->orderWeightWithPackagingInGrams);
        } else {
            //stay at lbs
            $unitCode = 'LBS';
            $unitDescription = 'Pounds';
            $weight = UnitConversions::grams_to_pounds($shippingQuote->orderWeightWithPackagingInGrams);
        }

        $payload = [
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
                                $shippingQuote->shipFrom->address1
                            ],
                            "City" => $shippingQuote->shipFrom->city,
                            "StateProvinceCode" => $shippingQuote->shipFrom->stateOrProvince,
                            "PostalCode" => $shippingQuote->shipFrom->postalCode,
                            "CountryCode" => $shippingQuote->shipFrom->countryCode
                        ]
                    ],
                    "ShipTo" => [
                        "Name" => "ShipToName",
                        "Address" => [
                            "AddressLine" => [
                                $shippingQuote->shipTo->address1
                            ],
                            "City" => $shippingQuote->shipTo->city,
                            "StateProvinceCode" => $shippingQuote->shipTo->stateOrProvince,
                            "PostalCode" => $shippingQuote->shipTo->postalCode,
                            "CountryCode" => $shippingQuote->shipTo->countryCode,
                        ]
                    ],
                    "ShipFrom" => [
                        "Name" => "ShipFromName",
                        "Address" => [
                            "AddressLine" => [
                                $shippingQuote->shipFrom->address1
                            ],
                            "City" => $shippingQuote->shipFrom->city,
                            "StateProvinceCode" => $shippingQuote->shipFrom->stateOrProvince,
                            "PostalCode" => $shippingQuote->shipFrom->postalCode,
                            "CountryCode" => $shippingQuote->shipFrom->countryCode
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
                    "ShipmentRatingOptions"=> [
                        "NegotiatedRatesIndicator"=> "Y"
                    ],
                    "Service" => [
                        "Code" => trim(strval($code)),
                        "Description" => "Surepost"
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
                                "Code" => $unitCode,
                                "Description" => $unitDescription
                            ],
                            "Weight" => number_format(round($weight,2,PHP_ROUND_HALF_UP), 1, '.', '')
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])
            ->withToken($token)
            ->post(env('UPS_LIVE_URL') . '/api/rating/v2403/Rate', $payload);

        $response->throw();

        return $response->json();
    }

    /**
     * getRatesAlmostAll
     * This method is responsible for calling the carrier api
     * and returning the responses as an array (of decoded responses)
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function getRatesAlmostAll(ShippingQuoteHeader $shippingQuote): array
    {
        $token = $this->getAccessToken();

        if (strlen($token) === 0) {
            throw new \Exception('No UPS token');
        }

        $payload = [
            "RateRequest" => [
                "Request" => [
                    "TransactionReference" => [
                        "CustomerContext" => "CustomerContext"
                    ]
                ],
                "Shipment" => [
                    "Shipper" => [
                        "Name" => "ShipperName",
                        "ShipperNumber" => env('UPS_SHIPPER_NUMBER'),
                        "Address" => [
                            "AddressLine" => [
                                $shippingQuote->shipFrom->address1
                            ],
                            "City" => $shippingQuote->shipFrom->city,
                            "StateProvinceCode" => $shippingQuote->shipFrom->stateOrProvince,
                            "PostalCode" => $shippingQuote->shipFrom->postalCode,
                            "CountryCode" => $shippingQuote->shipFrom->countryCode
                        ]
                    ],
                    "ShipTo" => [
                        "Name" => "ShipToName",
                        "Address" => [
                            "AddressLine" => [
                                $shippingQuote->shipTo->address1
                            ],
                            "City" => $shippingQuote->shipTo->city,
                            "StateProvinceCode" => $shippingQuote->shipTo->stateOrProvince,
                            "PostalCode" => $shippingQuote->shipTo->postalCode,
                            "CountryCode" => $shippingQuote->shipTo->countryCode
                        ]
                    ],
                    "ShipFrom" => [
                        "Name" => "ShipperName",
                        "Address" => [
                            "AddressLine" => [
                                $shippingQuote->shipFrom->address1
                            ],
                            "City" => $shippingQuote->shipFrom->city,
                            "StateProvinceCode" => $shippingQuote->shipFrom->stateOrProvince,
                            "PostalCode" => $shippingQuote->shipFrom->postalCode,
                            "CountryCode" => $shippingQuote->shipFrom->countryCode
                        ]
                    ],
                    "PaymentDetails" => [
                        "ShipmentCharge" => [
                            [
                                "Type" => "01",
                                "BillShipper" => [
                                    "AccountNumber" => env("UPS_SHIPPER_NUMBER")
                                ]
                            ]
                        ]
                    ],
                    "ShipmentRatingOptions" => [
                        "NegotiatedRatesIndicator" => "Y"
                    ],
                    "NumOfPieces" => "1",
                    "Package" => [
                        "PackagingType" => [
                            "Code" => "02",
                            "Description" => "Packaging"
                        ],
                        "PackageWeight" => [
                            "UnitOfMeasurement" => [
                                "Code" => "LBS",
                                "Description" => "Pounds"
                            ],
                            "Weight" => number_format(UnitConversions::grams_to_pounds($shippingQuote->orderWeightWithPackagingInGrams), 1, '.', '')
                        ]
                    ]
                ]
            ]
        ];

        //ask UPS for arrival estimates.
        //
        ///Shop returns prices only. /Shoptimeintransit returns the same prices
        //plus a TimeInTransit block per service, but requires the
        //DeliveryTimeInformation payload below. The old code called /Shop, so
        //there were never any dates to read - which is why generateReply()
        //fell back to a hardcoded window.
        //
        //this is the call that produces every rate at checkout, so a bad
        //request here means NO shipping options at all. we therefore try the
        //richer endpoint and quietly fall back to the original one on any
        //failure: worst case we lose the estimates, never the rates.
        $wantsTransit = config('shipping.request_transit_times', true);

        if ($wantsTransit) {
            $transitPayload = $payload;
            $transitPayload['RateRequest']['Shipment']['DeliveryTimeInformation'] = [
                //03 = non-document / package
                "PackageBillType" => "03",
                "Pickup" => [
                    "Date" => now()->format('Ymd'),
                    "Time" => now()->format('Hi')
                ]
            ];

            try {
                $response = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
                    ->timeout(env('HTTP_TIMEOUT', 5))
                    ->connectTimeout(env('HTTP_CONNECT', 2))
                    ->withHeaders([
                        'rs-request-id' => uniqid()
                    ])
                    ->withToken($token)
                    ->post(env('UPS_LIVE_URL') . '/api/rating/v2403/Shoptimeintransit', $transitPayload);

                $response->throw();

                return $response->json();
            } catch (\Throwable $e) {
                ApiRequestNote::newNote('error','UPS Shoptimeintransit failed, falling back to Shop',[
                    'error' => $e->getMessage()
                ]);
            }
        }

        $response = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])
            ->withToken($token)
            ->post(env('UPS_LIVE_URL') . '/api/rating/v2403/Shop', $payload);

        $response->throw();

        return $response->json();
    }

    protected function getAccessToken()
    {
        $upsResponse = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])
            ->asForm()
            ->withHeaders([
                'x-merchant-id' => env('UPS_MERCHANT_ID')
            ])
            ->withBasicAuth(env('UPS_CLIENT_ID'), env('UPS_CLIENT_SECRET'))
            ->post(env('UPS_LIVE_URL') . '/security/v1/oauth/token', [
                'grant_type' => 'client_credentials',
            ]);

        $upsResponse->throw();

        return $upsResponse->json('access_token');
    }

    /**
     * normalizeRates
     * This method is responsible for normalizing the returned
     * rates from getRates()
     *
     * @param array $responses
     *
     * @return Collection
     */
    public function normalizeRates(array $responses): Collection
    {
        $quotedServices = [];
        foreach($responses as $response) {
            //if we don't have the expected key return nothing
            if (!isset($response['RateResponse']['RatedShipment'])) {
                ApiRequestNote::newNote('error','UPS response missing RatedShipment',$response);
                continue;
            }

            if ($response['RateResponse']['Response']['ResponseStatus']['Code'] != "1") {
                ApiRequestNote::newNote('info','UPS code is not 1',$response);
            }

            foreach ($response['RateResponse']['RatedShipment'] as $row) {
                if (!array_key_exists('Service',$row)) {
                    //this may be a surepost item?
                    if (!isset($row['Service'])) {
                        //this is a surepost item
                        $row = $this->normalizeSurepost($response);
                        if (empty($row)) {
                            ApiRequestNote::newNote('error','Surepost unable to parse',$response);
                            continue;
                        }
                    }
                }

                $serviceCode = $row['Service']['Code'];
                $shipmentCost = $row['NegotiatedRateCharges']['TotalCharge']['MonetaryValue'];

                //ApiRequestNote::newNote('info','UPS code is '.$serviceCode,[$serviceCode,$shipmentCost]);

                $service = App::make(ShippingQuoteService::class);

                //to find the name of the service
                $name = 'UNKNOWN';
                $dbService = CarrierService::where('code','=',$serviceCode)->first();

                if (!is_null($dbService)) {
                    $name = $dbService->service_name;
                }

                $service->serviceName = $name;
                $service->serviceCode = $serviceCode;
                $service->serviceCost = $shipmentCost;

                $this->applyDeliveryEstimate($service, $row);

                $quotedServices[] = $service;
            }
        }

        return new Collection($quotedServices);
    }

    /**
     * applyDeliveryEstimate
     * Reads the arrival estimate out of a single RatedShipment row.
     *
     * Only populated when the rate came from /Shoptimeintransit. On the plain
     * /Shop fallback there is no TimeInTransit block and the service keeps
     * null dates, which are then omitted from the Shopify reply.
     *
     * @param ShippingQuoteService $service
     * @param array $row
     *
     * @return void
     */
    protected function applyDeliveryEstimate(ShippingQuoteService $service, array $row): void
    {
        $arrival = $row['TimeInTransit']['ServiceSummary']['EstimatedArrival'] ?? null;

        if (is_array($arrival)) {
            //UPS sends Ymd / His, sometimes nested under Arrival
            $date = TransitEstimate::fromUps(
                $arrival['Arrival']['Date'] ?? $arrival['Date'] ?? null,
                $arrival['Arrival']['Time'] ?? $arrival['Time'] ?? null
            );

            if (!is_null($date)) {
                $service->minDeliveryDate = $date;
                $service->maxDeliveryDate = $date;
                $service->deliveryEstimateSource = 'carrier';

                if (isset($arrival['BusinessDaysInTransit'])) {
                    $service->transitBusinessDays = (int) $arrival['BusinessDaysInTransit'];
                }

                return;
            }

            //no date, but a day count is still useful
            if (isset($arrival['BusinessDaysInTransit'])) {
                $days = (int) $arrival['BusinessDaysInTransit'];

                if ($days > 0) {
                    $service->transitBusinessDays = $days;
                    $service->minDeliveryDate = TransitEstimate::addBusinessDays($days);
                    $service->maxDeliveryDate = $service->minDeliveryDate;
                    $service->deliveryEstimateSource = 'business_days';

                    return;
                }
            }
        }

        //guaranteed services sometimes carry only this
        $guaranteedDays = $row['GuaranteedDelivery']['BusinessDaysInTransit'] ?? null;

        if (!is_null($guaranteedDays) && (int) $guaranteedDays > 0) {
            $days = (int) $guaranteedDays;

            $service->transitBusinessDays = $days;
            $service->minDeliveryDate = TransitEstimate::addBusinessDays($days);
            $service->maxDeliveryDate = $service->minDeliveryDate;
            $service->deliveryEstimateSource = 'business_days';
        }
    }

    protected function normalizeSurepost(array $response): array {
        //ApiRequestNote::newNote('debug','Surepost item',$response);
        $dot = new Data($response);
        $serviceCode = null;
        $serviceCost = null;

        if ($dot->has('RateResponse.RatedShipment.Service.Code')) {
            $serviceCode = $dot->get('RateResponse.RatedShipment.Service.Code');
        }

        if ($dot->has('RateResponse.RatedShipment.TotalCharges.MonetaryValue')) {
            $serviceCost = $dot->get('RateResponse.RatedShipment.TotalCharges.MonetaryValue');
        }

        if ((is_null($serviceCode)) || is_null($serviceCost)) {
            return [];
        }

        return [
            'Service' => [
                'Code' => $serviceCode
            ],
            'NegotiatedRateCharges' => [
                'TotalCharge' => [
                    'MonetaryValue' => $serviceCost
                ]
            ]
        ];
    }
}