<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ApiRequestNote;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use App\Support\TransitEstimate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\App;
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

    protected function getAccessToken()
    {
        /**
         * getAccessToken
         * This method is responsible for doing an oauth request and storing the token
         * In a "perfect" world we would actually cache this token
         * and use the cache for further entries (as long as the token was valid)
         */

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

        return $response->json('access_token', '');
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
        $token = $this->getAccessToken();

        //if the access token failed
        if (strlen($token) === 0) {
            Log::error('Fedex API Access Token Error', [$token]);
            throw new \Exception('Fedex API Access Token Error');
        }

        $body = [
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
                            "units" => "LB", "value" => UnitConversions::grams_to_pounds($shippingQuote->orderWeightWithPackagingInGrams)
                        ]
                    ]
                ]
            ]
        ];

        //--------------------------------------------------------------------
        // Delivery commitments (transit times)
        //
        // FIX: the previous code set
        //     $body['requestedShipment']['returnTransitTimes'] = true;
        // That flag belongs to FedEx's LEGACY SOAP Web Services API. It is NOT
        // a valid field on the REST Rate API (/rate/v1/rates/quotes) we call
        // here, so FedEx rejected the ENTIRE request with
        //   HTTP 400  "BAD.REQUEST.ERROR ... Missing or duplicate"
        // which dropped FedEx out of every quote at checkout.
        //
        // The REST Rate & Transit Times API instead returns the commit /
        // transitDays block automatically for eligible services once a planned
        // ship date (shipDateStamp) is supplied. normalizeRates() ->
        // applyDeliveryEstimate() already reads that block, so all we need to
        // do is send a valid ship date.
        //
        // We build the transit-enabled request as a SEPARATE body and try it
        // first; if FedEx ever rejects it we fall back to the plain request so
        // we always return prices, and at worst lose only the ETA - never the
        // rate. (Same defensive pattern used for the UPS transit call.)
        //--------------------------------------------------------------------
        if (config('shipping.request_transit_times', true)) {
            $transitBody = $body;
            $transitBody['requestedShipment']['shipDateStamp'] = now()->format('Y-m-d');

            try {
                $response = $this->postRateRequest($token, $transitBody);
                $response->throw();

                return [$response->json()];
            } catch (\Throwable $e) {
                $this->logFedexError('FedEx transit-time rate request failed, falling back to plain rate', $e);
            }
        }

        $response = $this->postRateRequest($token, $body);

        try {
            $response->throw();
        } catch (\Throwable $e) {
            $this->logFedexError('FedEx rate request failed', $e);
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
     * The exact keys vary by API version and account configuration. If dates
     * come back empty, dump a live response and check which of these paths is
     * actually populated:
     *
     *   ApiRequestNote::newNote('debug','fedex reply',$reply);
     *
     * @param ShippingQuoteService $service
     * @param array $reply
     *
     * @return void
     */
    protected function applyDeliveryEstimate(ShippingQuoteService $service, array $reply): void
    {
        //1. a committed delivery timestamp, most precise.
        //   commit.dateDetail.dayFormat is the REST field; the others are
        //   fallbacks seen on some services / API versions.
        $committed = $reply['commit']['dateDetail']['dayFormat']
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

        //3. nothing usable - leave null and omit from the reply
        ApiRequestNote::newNote('debug','fedex: no delivery estimate',[
            'serviceType' => $reply['serviceType'] ?? null
        ]);
    }
}
