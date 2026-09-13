<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ApiRequestNote;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Pool;
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
     * buildRatePayload
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    protected function buildRatePayload(ShippingQuoteHeader $shippingQuote): array
    {
        return [
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
    }

    /**
     * buildRateRequests
     * Adds ShipStation's rate request to the given HTTP connection pool so
     * it runs genuinely concurrently with every other carrier's requests
     * via curl_multi, instead of blocking the whole process in turn.
     * Laravel's Http client is synchronous, so wrapping it in an Amp fiber
     * alone does NOT make it non-blocking.
     *
     * @param Pool $pool
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function buildRateRequests(Pool $pool, ShippingQuoteHeader $shippingQuote): array
    {
        $request = $pool->as('usps')
            ->withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders(['rs-request-id' => uniqid()])
            ->withBasicAuth(env('USPS_API_KEY'), env('USPS_API_SECRET_KEY'));

        $partnerId = env('SHIPSTATION_PARTNER', '');
        if (strlen($partnerId) > 0) {
            $request = $request->withHeaders([
                'x-partner' => $partnerId
            ]);
        }

        return [
            'usps' => $request->post(env('USPS_LIVE_URL') . "/shipments/getrates", $this->buildRatePayload($shippingQuote))
        ];
    }

    /**
     * parseRatesResponses
     * Validates the pooled response and returns it in the same shape the
     * old getRates() used to return.
     *
     * @param array $responses keyed the same way buildRateRequests() named them
     *
     * @return array
     */
    public function parseRatesResponses(array $responses): array
    {
        $response = $responses['usps'];
        $response->throw();

        return [$response->json()];
    }

    /**
     * buildFallbackRates
     * ShipStation has no enhanced/plain distinction, so there is nothing
     * meaningful to retry - a second attempt with an identical request is
     * unlikely to succeed where the first didn't. This matches the old
     * behaviour, which never retried USPS either.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function buildFallbackRates(ShippingQuoteHeader $shippingQuote): array
    {
        ApiRequestNote::newNote('error', 'ShipStation has no fallback rate request');

        throw new Exception('ShipStation rate request failed and has no fallback');
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