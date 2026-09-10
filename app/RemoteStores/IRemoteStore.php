<?php

namespace App\RemoteStores;

use App\Models\ShippingQuoteHeader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IRemoteStore
{
    /**
     * getRemoteStoreType
     * Returns the remote store type as a string
     * ie. magento, shopify, woocommerce
     *
     * @return string
     */
    public function getRemoteStoreType(): string;

    /**
     * preValidationModify
     * The method is built to allow developers to modify the
     * request object before validation routines are run
     *
     * @param Request $request
     *
     * @return void
     */
    public function preValidationModify(Request $request): void;

    /**
     * validationInput
     * This method allows the developer to validate the input data
     * (mostly via Request class) to ensure that the remote store
     * is sending proper data
     *
     * @param Request $request
     *
     * @return bool
     */
    public function validateInput(Request $request): bool;

    /**
     * postValidationModify
     * This method allows the developer to modify the request data
     * after validation occurs
     *
     * @param Request $request
     *
     * @return void
     */
    public function postValidationModify(Request $request): void;

    /**
     * normalizeInput
     * This method is responsible for constructing a ShippingQuoteHeader
     *
     * @param Request $request
     *
     * @return ShippingQuoteHeader
     */
    public function normalizeInput(Request $request): ShippingQuoteHeader;

    /**
     * generateReply
     * This method is responsible for assembling the reply to
     * the remote store.
     *
     * @param Collection $services
     *
     * @return array
     */
    public function generateReply(Collection $services): array;
}