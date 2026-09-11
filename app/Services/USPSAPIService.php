<?php

namespace App\Services;

use App\Models\CarrierService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class USPSAPIService
{
//    


    public function shippingRates($origin, $destination, $weight, $currency, $locale)
    {
        try {
            $client = new Client();

            // Set API credentials and endpoint
            $authHeader = base64_encode(env('USPS_API_KEY') . ':' . env('USPS_API_SECRET_KEY'));
            $headers = [
                'Authorization' => "Basic $authHeader",
                'Content-Type' => 'application/json',
            ];

            // Convert weight from grams to ounces and round to nearest whole number
            $weightOz = (int)round($weight * 0.035274);

            // Prepare payload using provided origin, destination, and weight information
            $payload = [
                'carrierCode' => 'stamps_com',
                'fromPostalCode' => $origin['postal_code'],
                'toState' => $destination['province'],
                'toCountry' => $destination['country'],
                'toPostalCode' => $destination['postal_code'],
                'toCity' => $destination['city'],
                'weight' => [
                    'value' => $weightOz,
                    'units' => 'OZ',
                ],
                'confirmation' => 'delivery',
                'residential' => false,
            ];

            // Send POST request to get rates
            $response = $client->post(env('USPS_LIVE_URL') . "/shipments/getrates", [
                'headers' => $headers,
                'json' => $payload,
            ]);

            $rates = json_decode($response->getBody()->getContents(), true);

            // Ensure rates are not empty and filter allowed carrier services
            if (!empty($rates)) {
                $allowedServiceCodes = CarrierService::where('carrier_name', 'usps')
                    ->where('api_allowed', 1)
                    ->pluck('code')
                    ->toArray();

                // Filter and format rates based on allowed carrier services
                return array_values(array_map(function ($rate) use ($currency) {
                    return [
                        'service_name' => $rate['serviceName'],
                        'service_code' => $rate['serviceCode'],
                        'total_price' => $rate['shipmentCost'],
                        'currency' => $currency,
                        'min_delivery_date' => $rate['deliveryTime'] ?? 'N/A',
                        'max_delivery_date' => $rate['deliveryTime'] ?? 'N/A',
                    ];
                }, array_filter($rates, function ($rate) use ($allowedServiceCodes) {
                    return in_array($rate['serviceCode'], $allowedServiceCodes);
                })));
            }

        } catch (RequestException $ex) {
            Log::error('USPS Service issue: ' . $ex->getMessage());
            return [];
        }
    }


}

