<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ApiRequestNote;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use App\Support\TransitEstimate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Fedex implements IApiProvider
{

    /**
     * getApiProviderName
     * Returns the API Provider name as a string
     *
     * @return string
     */
    public function getApiProviderName(): string
    {
        return 'fedex';
    }

    /**
     * getCarrierName
     * Returns the carrier name
     *
     * @return string
     */
    public function getCarrierName(): string
    {
        return 'fedex';
    }

    /**
     * getAccessToken
     * Returns a cached FedEx OAuth token when we have a live one, otherwise
     * fetches a new one and caches it. Shopify's carrier-service timeout is
     * as low as 3-10 seconds depending on the store's request volume - an
     * extra OAuth round trip on every single rate request eats directly into
     * that budget for no reason, since these tokens are valid for a full
     * hour.
     *
     * @return string
     */
    protected function getAccessToken()
    {
        $cacheKey = 'fedex_access_token';

        $cached = Cache::get($cacheKey);
        if (!empty($cached)) {
            return $cached;
        }

        $response = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])
            ->AsForm()
            ->post(env('FEDEX_LIVE_URL') . '/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => env('FEDEX_API_KEY'),       // Replace with your actual FedEx API key
                'client_secret' => env('FEDEX_SECRET_KEY'), // Replace with your actual FedEx secret key
                'scope' => '', // Adjust the scope if needed
            ]);

        //if the response from fedex was not successful log the actual error
        //FedEx sent back (status + body), not just the exception message
        try {
            $response->throw();
        } catch (\Throwable $e) {
            $this->logFedexError('FedEx OAuth token request failed', $e);
            throw $e;
        }

        $token = $response->json('access_token', '');

        if (!empty($token)) {
            //2 minute safety margin so we never hand out a token that's
            //about to expire mid-request
            $ttl = max(60, (int) $response->json('expires_in', 3600) - 120);
            Cache::put($cacheKey, $token, $ttl);
        }

        return $token;
    }

    /**
     * buildRateBody
     * Builds the plain (no transit-time request) FedEx rate request body.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    protected function buildRateBody(ShippingQuoteHeader $shippingQuote): array
    {
        return [
            "accountNumber" => [
                "value" => env('FEDEX_ACCOUNT_NO')
            ],
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "postalCode" => $shippingQuote->shipFrom->postalCode,
                        "countryCode" => $shippingQuote->shipFrom->countryCode
                    ]
                ],
                "recipient" => [
                    "address" => [
                        "postalCode" => $shippingQuote->shipTo->postalCode,
                        "countryCode" => $shippingQuote->shipTo->countryCode,
                        'residential' => true
                    ]
                ],
                "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                "rateRequestType" => [
                    "ACCOUNT",
                    "LIST"
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            //FedEx rejects weight values with more than 2 decimal
                            //places as PACKAGE.WEIGHT.INVALID. grams_to_pounds()
                            //returns 4 decimal places, so it must be rounded before
                            //being sent.
                            "units" => "LB", "value" => round(UnitConversions::grams_to_pounds($shippingQuote->orderWeightWithPackagingInGrams), 2)
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * buildTransitBody
     * Builds the transit-enabled FedEx rate request body.
     *
     * returnTransitTimes IS a valid REST field (confirmed against FedEx's
     * own OpenAPI schema for this endpoint), but it lives under a TOP-LEVEL
     * "rateRequestControlParameters" object - a sibling of requestedShipment,
     * not a field inside it. Every previous attempt (including the one that
     * got a 400 and the one that silently did nothing) put it in the wrong
     * place.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    protected function buildTransitBody(ShippingQuoteHeader $shippingQuote): array
    {
        $body = $this->buildRateBody($shippingQuote);
        $body['requestedShipment']['shipDateStamp'] = now()->format('Y-m-d');
        $body['rateRequestControlParameters'] = [
            'returnTransitTimes' => true
        ];

        return $body;
    }

    /**
     * buildRateRequests
     * Adds FedEx's best-effort (transit-enabled) rate request to the given
     * HTTP connection pool so it runs genuinely concurrently with every
     * other carrier's requests via curl_multi, instead of blocking the
     * whole process in turn - Laravel's Http client is synchronous, so
     * wrapping it in an Amp fiber alone does NOT make it non-blocking.
     *
     * @param Pool $pool
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function buildRateRequests(Pool $pool, ShippingQuoteHeader $shippingQuote): array
    {
        $token = $this->getAccessToken();

        if (strlen($token) === 0) {
            Log::error('Fedex API Access Token Error', [$token]);
            throw new \Exception('Fedex API Access Token Error');
        }

        $useTransitBody = config('shipping.request_transit_times', true);
        $body = $useTransitBody ? $this->buildTransitBody($shippingQuote) : $this->buildRateBody($shippingQuote);

        return [
            'fedex' => $pool->as('fedex')
                ->withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
                ->timeout(env('HTTP_TIMEOUT', 5))
                ->connectTimeout(env('HTTP_CONNECT', 2))
                ->withHeaders([
                    'rs-request-id' => uniqid()
                ])
                ->withToken($token)
                ->post(env('FEDEX_LIVE_URL') . '/rate/v1/rates/quotes', $body)
        ];
    }

    /**
     * parseRatesResponses
     * Validates the pooled response and returns it in the same shape the
     * old getRates() used to return. Throws if FedEx rejected the request,
     * signalling the caller to try buildFallbackRates() instead.
     *
     * @param array $responses keyed the same way buildRateRequests() named them
     *
     * @return array
     */
    public function parseRatesResponses(array $responses): array
    {
        $response = $responses['fedex'];

        try {
            $response->throw();
        } catch (\Throwable $e) {
            $this->logFedexError('FedEx transit-time rate request failed, falling back to plain rate', $e);
            throw $e;
        }

        return [$response->json()];
    }

    /**
     * buildFallbackRates
     * Blocking plain (no transit-time) rate request, used only when the
     * pooled transit-enabled attempt failed. This is the rare path, so
     * blocking here is an acceptable trade - resilience over speed.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function buildFallbackRates(ShippingQuoteHeader $shippingQuote): array
    {
        $token = $this->getAccessToken();

        if (strlen($token) === 0) {
            Log::error('Fedex API Access Token Error', [$token]);
            throw new \Exception('Fedex API Access Token Error');
        }

        $response = $this->postRateRequest($token, $this->buildRateBody($shippingQuote));

        try {
            $response->throw();
        } catch (\Throwable $e) {
            $this->logFedexError('FedEx fallback rate request failed', $e);
            throw $e;
        }

        return [$response->json()];
    }

    /**
     * logFedexError
     * Logs a FedEx API failure with the actual status code and response body
     * FedEx sent back, not just the exception message - that detail is what
     * actually explains WHY a request failed (e.g. an invalid field, expired
     * token, bad account number) instead of just that it did.
     *
     * @param string $message
     * @param \Throwable $e
     *
     * @return void
     */
    protected function logFedexError(string $message, \Throwable $e): void
    {
        $context = ['error' => $e->getMessage()];

        if ($e instanceof RequestException) {
            $context['status'] = $e->response->status();
            $context['body'] = $e->response->json() ?? $e->response->body();
        }

        ApiRequestNote::newNote('error', $message, $context);
    }

    /**
     * postRateRequest
     * Sends a rate request body to the FedEx REST Rate API and returns the
     * raw HTTP response (not yet ->throw()n, so the caller can decide).
     *
     * @param string $token
     * @param array  $body
     *
     * @return \Illuminate\Http\Client\Response
     */
    protected function postRateRequest(string $token, array $body)
    {
        return Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])
            ->withToken($token)
            ->post(env('FEDEX_LIVE_URL') . '/rate/v1/rates/quotes', $body);
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
        foreach ($responses as $data) {
            if (!isset($data['output']['rateReplyDetails'])) {
                ApiRequestNote::newNote('error', 'FedEx response missing rateReplyDetails', $data);
                throw new \Exception('Fedex API Error: response missing rateReplyDetails');
            }

            foreach ($data['output']['rateReplyDetails'] as $reply) {
                $service = App::make(ShippingQuoteService::class);
                $service->serviceName = $reply['serviceDescription']['names'][0]['value'];
                $service->serviceCode = $reply['serviceType'];
                $service->serviceCost = $reply['ratedShipmentDetails'][0]['totalNetCharge'];

                $this->applyDeliveryEstimate($service, $reply);

                $quotedServices[] = $service;
            }
        }

        return new Collection($quotedServices);
    }

    /**
     * applyDeliveryEstimate
     * Pulls the delivery commitment out of a single rateReplyDetails entry.
     *
     * FedEx exposes this in more than one place depending on the service and
     * the lane, so we try the committed date first and fall back to the
     * transit-day enum. If neither is present the service keeps null dates and
     * is returned to Shopify without an estimate - which is valid, and far
     * better than inventing one.
     *
     * Field names verified against FedEx's own OpenAPI schema for this
     * endpoint (developer.fedex.com/wirc/json/api_groups/Rate/RateQuotes-Resource.json).
     * If dates still come back empty, the debug note below (still logged when
     * every path misses) dumps the full reply for inspection.
     *
     * @param ShippingQuoteService $service
     * @param array $reply
     *
     * @return void
     */
    protected function applyDeliveryEstimate(ShippingQuoteService $service, array $reply): void
    {
        //1. a committed delivery timestamp, most precise.
        //   commit.dateDetail.dayFormat and operationalDetail.commitDate are
        //   both confirmed fields on FedEx's own OpenAPI schema for this
        //   endpoint - commitDate in particular was the one field FedEx
        //   populated on every service in their own reference example, even
        //   when deliveryDate was empty. The rest are fallbacks seen on some
        //   services / API versions.
        $committed = $reply['commit']['dateDetail']['dayFormat']
            ?? $reply['operationalDetail']['commitDate']
            ?? $reply['commit']['derivedDeliveryDate']
            ?? $reply['operationalDetail']['deliveryDate']
            ?? $reply['operationalDetail']['deliveryDay']
            ?? null;

        $date = TransitEstimate::fromIso($committed);

        if (!is_null($date)) {
            $service->minDeliveryDate = $date;
            $service->maxDeliveryDate = $date;
            $service->deliveryEstimateSource = 'carrier';

            return;
        }

        //2. a transit-day count, e.g. "TWO_DAYS" or a numeric transit count.
        $transitEnum = $reply['commit']['transitDays']['description']
            ?? $reply['commit']['transitDays']['minimumTransitTime']
            ?? $reply['operationalDetail']['transitTime']
            ?? null;

        $days = TransitEstimate::fedexTransitDays($transitEnum);

        if (!is_null($days)) {
            $service->transitBusinessDays = $days;
            $service->minDeliveryDate = TransitEstimate::addBusinessDays($days);
            $service->maxDeliveryDate = $service->minDeliveryDate;
            $service->deliveryEstimateSource = 'business_days';

            return;
        }

        //3. nothing usable - leave null and omit from the reply.
        //   log the full reply (not just serviceType) so we can see exactly
        //   what FedEx sent back and find the field this account/lane
        //   actually populates.
        ApiRequestNote::newNote('debug','fedex: no delivery estimate',$reply);
    }
}
