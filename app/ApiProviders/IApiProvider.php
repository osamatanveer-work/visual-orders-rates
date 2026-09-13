<?php

namespace App\ApiProviders;

use App\Models\ShippingQuoteHeader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Pool;

interface IApiProvider
{
    /**
     * getApiProviderName
     * Returns the API Provider name as a string
     *
     * @return string
     */
    public function getApiProviderName(): string;

    /**
     * getCarrierName
     * Returns the carrier name
     *
     * @return string
     */
    public function getCarrierName(): string;

    /**
     * buildRateRequests
     * Adds this provider's rate request(s) to the given HTTP connection
     * pool so they run genuinely concurrently - via curl_multi - with every
     * other carrier's requests (and with each other, for a provider that
     * needs more than one call). Laravel's Http client is synchronous, so
     * wrapping it in an Amp fiber alone does not make it non-blocking;
     * pooling is what actually achieves concurrency.
     *
     * Keys must be unique across the whole pool - namespace them with the
     * carrier/provider name to avoid collisions with other providers.
     *
     * @param Pool $pool
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array keyed by a unique request name
     */
    public function buildRateRequests(Pool $pool, ShippingQuoteHeader $shippingQuote): array;

    /**
     * parseRatesResponses
     * Given the resolved pool responses, keyed exactly as buildRateRequests()
     * named them, returns the same array shape normalizeRates() expects.
     * Throws if the response(s) indicate total failure, signalling the
     * caller to try buildFallbackRates() instead.
     *
     * @param array $responses keyed the same way buildRateRequests() named them
     *
     * @return array
     */
    public function parseRatesResponses(array $responses): array;

    /**
     * buildFallbackRates
     * Synchronous, blocking fallback used only when the pooled attempt
     * failed entirely. This is the rare path, so blocking here is an
     * acceptable trade - resilience over speed. Providers with nothing
     * meaningful to fall back to should just throw.
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function buildFallbackRates(ShippingQuoteHeader $shippingQuote): array;

    /**
     * normalizeRates
     * This method is responsible for normalizing the returned
     * rates from parseRatesResponses()/buildFallbackRates()
     *
     * @param array $responses
     *
     * @return Collection
     */
    public function normalizeRates(array $responses): Collection;
}