<?php

namespace App\ApiProviders;

use App\Libraries\UnitConversions;
use App\Models\ApiRequestNote;
use App\Models\CarrierService;
use App\Models\ShippingQuoteHeader;
use App\Models\ShippingQuoteService;
use App\Support\TransitEstimate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Dflydev\DotAccessData\Data;

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
     * buildRatePayload
     * Builds the main (non-SurePost) UPS rate request payload.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    protected function buildRatePayload(ShippingQuoteHeader $shippingQuote): array
    {
        return [
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
    }

    /**
     * buildTransitPayload
     * /Shop returns prices only. /Shoptimeintransit returns the same prices
     * plus a TimeInTransit block per service, but requires the
     * DeliveryTimeInformation payload added here.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    protected function buildTransitPayload(ShippingQuoteHeader $shippingQuote): array
    {
        $payload = $this->buildRatePayload($shippingQuote);
        $payload['RateRequest']['Shipment']['DeliveryTimeInformation'] = [
            //03 = non-document / package
            "PackageBillType" => "03",
            "Pickup" => [
                "Date" => now()->format('Ymd'),
                "Time" => now()->format('Hi')
            ]
        ];

        return $payload;
    }

    /**
     * buildSurePostPayload
     *
     * @param ShippingQuoteHeader $shippingQuote
     * @param int $code
     *
     * @return array
     */
    protected function buildSurePostPayload(ShippingQuoteHeader $shippingQuote, int $code): array
    {
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

        return [
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
    }

    /**
     * getSurePostCode
     * Returns the SurePost service code applicable for this shipment's
     * weight, or null if SurePost does not apply (non-US lane, or too heavy).
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return int|null
     */
    protected function getSurePostCode(ShippingQuoteHeader $shippingQuote): ?int
    {
        if ((strtoupper($shippingQuote->shipTo->countryCode) != 'US') || ($shippingQuote->shipFrom->countryCode != 'US')) {
            return null;
        }

        $lbsConversion = UnitConversions::grams_to_pounds($shippingQuote->orderWeightWithPackagingInGrams);

        if ($lbsConversion <= 1) {
            return 92;
        }

        if ($lbsConversion >= 1 && $lbsConversion <= 10) {
            return 93;
        }

        return null;
    }

    /**
     * buildRateRequests
     * Adds UPS's rate request(s) - the main transit-enabled quote, plus an
     * optional SurePost quote for lightweight US-to-US shipments - to the
     * given HTTP connection pool so they run genuinely concurrently with
     * each other AND every other carrier's requests via curl_multi, instead
     * of blocking the whole process in turn one at a time. Laravel's Http
     * client is synchronous, so wrapping it in an Amp fiber alone does NOT
     * make it non-blocking.
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
            throw new \Exception('No UPS token');
        }

        $useTransit = config('shipping.request_transit_times', true);
        $mainPayload = $useTransit ? $this->buildTransitPayload($shippingQuote) : $this->buildRatePayload($shippingQuote);
        $mainEndpoint = $useTransit ? 'Shoptimeintransit' : 'Shop';

        $requests = [
            'ups_main' => $pool->as('ups_main')
                ->withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
                ->timeout(env('HTTP_TIMEOUT', 5))
                ->connectTimeout(env('HTTP_CONNECT', 2))
                ->withHeaders(['rs-request-id' => uniqid()])
                ->withToken($token)
                ->post(env('UPS_LIVE_URL') . '/api/rating/v2403/' . $mainEndpoint, $mainPayload)
        ];

        $surePostCode = $this->getSurePostCode($shippingQuote);

        if (!is_null($surePostCode)) {
            $requests['ups_surepost'] = $pool->as('ups_surepost')
                ->withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
                ->timeout(env('HTTP_TIMEOUT', 5))
                ->connectTimeout(env('HTTP_CONNECT', 2))
                ->withHeaders(['rs-request-id' => uniqid()])
                ->withToken($token)
                ->post(env('UPS_LIVE_URL') . '/api/rating/v2403/Rate', $this->buildSurePostPayload($shippingQuote, $surePostCode));
        }

        return $requests;
    }

    /**
     * parseRatesResponses
     * Validates the pooled responses and returns them in the same shape the
     * old getRates() used to return. Throws only if the MAIN quote failed -
     * that's the one used as the fallback trigger, matching the old
     * behaviour where Shoptimeintransit failing fell back to Shop. SurePost
     * has no fallback of its own (it never did) - if it failed, it simply
     * contributes nothing.
     *
     * @param array $responses keyed the same way buildRateRequests() named them
     *
     * @return array
     */
    public function parseRatesResponses(array $responses): array
    {
        $mainResponse = $responses['ups_main'];

        try {
            $mainResponse->throw();
        } catch (\Throwable $e) {
            ApiRequestNote::newNote('error', 'UPS main rate request failed, falling back to Shop', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }

        $rates = [$mainResponse->json()];

        if (isset($responses['ups_surepost'])) {
            try {
                $responses['ups_surepost']->throw();
                $rates[] = $responses['ups_surepost']->json();
            } catch (\Throwable $e) {
                ApiRequestNote::newNote('error', 'UPS SurePost request failed', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $rates;
    }

    /**
     * buildFallbackRates
     * Blocking plain (/Shop, no transit data) rate request, used only when
     * the pooled main quote failed. This is the rare path, so blocking here
     * is an acceptable trade - resilience over speed. Does not retry
     * SurePost, matching its pre-existing no-fallback behaviour.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function buildFallbackRates(ShippingQuoteHeader $shippingQuote): array
    {
        $token = $this->getAccessToken();

        if (strlen($token) === 0) {
            throw new \Exception('No UPS token');
        }

        $response = Http::withUserAgent(env('HTTP_USERAGENT', 'GuzzleHttp/7'))
            ->timeout(env('HTTP_TIMEOUT', 5))
            ->connectTimeout(env('HTTP_CONNECT', 2))
            ->withHeaders(['rs-request-id' => uniqid()])
            ->withToken($token)
            ->post(env('UPS_LIVE_URL') . '/api/rating/v2403/Shop', $this->buildRatePayload($shippingQuote));

        $response->throw();

        return [$response->json()];
    }

    /**
     * getAccessToken
     * Returns a cached UPS OAuth token when we have a live one, otherwise
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
        $cacheKey = 'ups_access_token';

        $cached = Cache::get($cacheKey);
        if (!empty($cached)) {
            return $cached;
        }

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

        $token = $upsResponse->json('access_token');

        if (!empty($token)) {
            //2 minute safety margin so we never hand out a token that's
            //about to expire mid-request
            $ttl = max(60, (int) $upsResponse->json('expires_in', 3600) - 120);
            Cache::put($cacheKey, $token, $ttl);
        }

        return $token;
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