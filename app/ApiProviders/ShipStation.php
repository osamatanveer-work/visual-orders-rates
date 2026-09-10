<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class ShipStation implements IApiProvider
{

    /**
     * getApiProviderName
     * Returns the API Provider name as a string
     *
     * @return string
     */
    public function getApiProviderName(): string
    {
        return 'shipstation';
    }

    /**
     * getCarrierName
     * Returns the carrier name
     *
     * @return string
     */
    public function getCarrierName(): string
    {
        return 'usps';
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
        // Prepare payload
        $payload = [
            'carrierCode' => 'stamps_com',
            'fromPostalCode' => $shippingQuote->shipFrom->postalCode,
            'fromCity' => $shippingQuote->shipFrom->city,
            'fromState' => $shippingQuote->shipFrom->stateOrProvince,
            'toState' => $shippingQuote->shipTo->stateOrProvince,
            'toCountry' => $shippingQuote->shipTo->countryCode,
            'toPostalCode' => $shippingQuote->shipTo->postalCode,
            'toCity' => $shippingQuote->shipTo->city,
            'weight' => [
                'value' => $shippingQuote->orderWeightWithPackagingInGrams,
                'units' => 'grams',
            ],
            'confirmation' => 'delivery',
            'residential' => true,
        ];

        $uspsResponse = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])->withBasicAuth(env('USPS_API_KEY'), env('USPS_API_SECRET_KEY'));

        $partnerId = env('SHIPSTATION_PARTNER', '');
        if (strlen($partnerId) > 0) {
            $uspsResponse = $uspsResponse->withHeaders([
                'x-partner' => $partnerId
            ]);
        }

        $uspsResponse = $uspsResponse->post(env('USPS_LIVE_URL') . "/shipments/getrates", $payload);

        // Throw an exception if a client or server error occurred...
        $uspsResponse->throw();

        return [
            $uspsResponse->json()
        ];
    }

    /**
     * normalizeRates
     * This method is responsible for normalizing the returned
     * rates from getRates()
     *
     * @param array $responses
     * @param ShippingQuoteHeader $quoteHeader
     *
     * @return Collection
     */
    public function normalizeRates(array $responses): Collection
    {
        if (count($responses) === 0) {
            throw new Exception('no responses');
        }

        $quoteServices = [];

        foreach ($responses as $response) {
            foreach ($response as $data) {
                $service = App::make(ShippingQuoteService::class);
                $service->serviceName = $data['serviceName'];
                $service->serviceCode = $data['serviceCode'];
                $service->serviceCost = $data['shipmentCost'] + $data['otherCost'];

                //NO DELIVERY ESTIMATE IS AVAILABLE HERE.
                //
                //ShipStation's /shipments/getrates returns serviceName,
                //serviceCode, shipmentCost and otherCost - and nothing about
                //transit time. There is no field to read, so minDeliveryDate
                //and maxDeliveryDate stay null and generateReply() omits both
                //keys for these services. That is correct behaviour: Shopify
                //treats them as optional.
                //
                //If USPS estimates are wanted at checkout, the options are:
                //  - move USPS onto a rating call that returns transit data
                //    (e.g. USPS's own API, which exposes standardized service
                //    commitments), or
                //  - map a static business-day estimate per serviceCode here,
                //    accepting that it is a guess rather than a commitment.
                //
                //Do not reintroduce a blanket window across all carriers -
                //that is exactly the behaviour this change removed.

                $quoteServices[] = $service;
            }
        }

        return new Collection($quoteServices);
    }
}