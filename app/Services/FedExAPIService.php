<?php

namespace App\Services;

use App\Models\CarrierService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class FedExAPIService
{
    public function shippingRates($origin, $destination, $weight, $currency, $local)
    {
        $response = ['rates' => []];
        $weightLbs = round($weight / 453.59237, 2); // Convert weight from grams to pounds

        try {
            $client = new Client();
            $token = $this->getAccessToken();

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token->access_token,
            ];

            $isDomestic = $origin['country'] === $destination['country'];
            $body = [
                "accountNumber" => ["value" => env('FEDEX_ACCOUNT_NO')],
                "requestedShipment" => [
                    "shipper" => ["address" => ["postalCode" => $origin['postal_code'], "countryCode" => $origin['country']]],
                    "recipient" => [
                        "address" => [
                                "postalCode" => $destination['postal_code'],
                                "countryCode" => $destination['country']
                            ] + ($isDomestic ? ['residential' => true] : [])
                    ],
                    "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                    "rateRequestType" => ["ACCOUNT", "LIST"],
                    "requestedPackageLineItems" => [["weight" => ["units" => "LB", "value" => $weightLbs]]]
                ]
            ];

            $fedexResponse = $client->post(env('FEDEX_LIVE_URL') . '/rate/v1/rates/quotes', [
                'headers' => $headers,
                'json' => $body,
            ]);

            $rateReplyDetails = json_decode($fedexResponse->getBody()->getContents())->output->rateReplyDetails ?? null;

            if ($rateReplyDetails) {
                $allowedServices = CarrierService::where('carrier_name', 'Fedex')
                    ->where('api_allowed', 1)
                    ->pluck('service_name')
                    ->push('FedEx Home Delivery')
                    ->toArray();

                foreach ($rateReplyDetails as $rate) {
                    if (in_array($rate->serviceType, $allowedServices)) {
                        $response['rates'][] = [
                            'service_name' => $rate->serviceDescription->names[0]->value ?? $rate->serviceType,
                            'service_code' => $rate->serviceType ?? 'Unknown Code',
                            'total_price' => $rate->ratedShipmentDetails[0]->totalNetCharge ?? 0,
                            'currency' => $rate->ratedShipmentDetails[0]->currency ?? 'USD',
                            'min_delivery_date' => $rate->operationalDetail->astraDescription ?? null,
                            'max_delivery_date' => $rate->operationalDetail->astraDescription ?? null,
                        ];
                    }
                }
            }

            return $response['rates'];
        } catch (RequestException $ex) {
            Log::error('FedEx API Issue: ' . $ex->getMessage());
            return [];
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
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('FEDEX_API_KEY'),       // Replace with your actual FedEx API key
                'client_secret' => env('FEDEX_SECRET_KEY'), // Replace with your actual FedEx secret key
                'scope' => '', // Adjust the scope if needed
            ],
        ];

        try {
            // Send the POST request to FedEx's OAuth token endpoint
            $response = $client->post(env('FEDEX_LIVE_URL') . '/oauth/token', $options);

            // Get the body of the response as a string
            $body = $response->getBody()->getContents();

            // Return the access token (or handle it as needed)

            return json_decode($body);
        } catch (RequestException $ex) {
            // Catch and handle exceptions if the request fails
            echo 'Error: ' . $ex->getMessage();
        }
    }


}