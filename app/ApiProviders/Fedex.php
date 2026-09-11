<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ApiRequestNote;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use App\Support\TransitEstimate;
use Illuminate\Database\Eloquent\Collection;
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

        //if the response from fedex was not successful then log an error and return
        //an empty string
        $response->throw();

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

        //ask for delivery commitments. without this flag the reply carries no
        //commit/operationalDetail transit data at all, which is why the old
        //code had nothing to work with and fell back to a hardcoded window.
        if (config('shipping.request_transit_times', true)) {
            $body['requestedShipment']['returnTransitTimes'] = true;
        }

        $response = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders([
                'rs-request-id' => uniqid()
            ])
            ->withToken($token)
            ->post(env('FEDEX_LIVE_URL') . '/rate/v1/rates/quotes', $body);

        $response->throw();

        return [$response->json()];
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
                throw new \Exception('Fedex API Error x-2');
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
        //1. a committed delivery timestamp, most precise
        $committed = $reply['commit']['dateDetail']['dayFormat']
            ?? $reply['operationalDetail']['deliveryDate']
            ?? $reply['commit']['derivedDeliveryDate']
            ?? null;

        $date = TransitEstimate::fromIso($committed);

        if (!is_null($date)) {
            $service->minDeliveryDate = $date;
            $service->maxDeliveryDate = $date;
            $service->deliveryEstimateSource = 'carrier';

            return;
        }

        //2. a transit-day count, e.g. "TWO_DAYS"
        $transitEnum = $reply['operationalDetail']['transitTime']
            ?? $reply['commit']['transitDays']['description']
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