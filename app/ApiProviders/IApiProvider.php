<?php

namespace App\ApiProviders;

use App\Models\ShippingQuoteHeader;
use Illuminate\Database\Eloquent\Collection;

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
     * getRates
     * This method is responsible for calling the carrier api
     * and returning the responses as an array (of decoded responses)
     *
     * @param ShippingQuoteHeader $shippingQuote
     *
     * @return array
     */
    public function getRates(ShippingQuoteHeader $shippingQuote): array;

    /**
     * normalizeRates
     * This method is responsible for normalizing the returned
     * rates from getRates()
     *
     * @param array $responses
     *
     * @return Collection
     */
    public function normalizeRates(array $responses): Collection;
}