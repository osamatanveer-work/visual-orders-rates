<?php

namespace App\Listeners;

use App\Events\ShippingQuoteCompleted;
use App\Models\ApiRequestNote;
use App\Models\StoreBannedCarrier;

class RemoveBannedCountryCarriers
{
    protected StoreBannedCarrier $storeBannedCarrier;
    /**
     * Create the event listener.
     */
    public function __construct(StoreBannedCarrier $storeBannedCarrier)
    {
        $this->storeBannedCarrier = $storeBannedCarrier;
    }

    /**
     * Handle the event.
     */
    public function handle(ShippingQuoteCompleted $event): void
    {
        ApiRequestNote::newNote('debug','Remove Banned Country Carriers');
        $shippingQuote = $event->shippingQuote;

        //do we have any banned carriers?
        $bannedCarriers = $this->storeBannedCarrier->where('store_id','=',$shippingQuote->store_id)->first();

        if (is_null($bannedCarriers)) {
            ApiRequestNote::newNote('debug','No Bans for this store');
            return;
        }

        //does this request ship to a banned carrier country?
        $shipToCountry = strtoupper(trim($shippingQuote->shipTo->countryCode));
        $shippingToBannedCountry = false;
        foreach ($bannedCarriers->countries as $country) {
            $country = strtoupper(trim($country));
            if ($country === $shipToCountry) {
                $shippingToBannedCountry = true;
                break;
            }
        }

        if (!$shippingToBannedCountry) {
            ApiRequestNote::newNote('debug','Destination Country does not match banned country');
            return;
        }

        ApiRequestNote::newNote('debug','Destination Country matches banned country');

        foreach ($shippingQuote->services as $service) { //this is a ShippingQuoteService
            if ($service->isFiltered === true) {
                continue;
            }

            $carrierService = $service->service; //This is a CarrierService
            if (is_null($carrierService)) {
                ApiRequestNote::newNote('debug','Quote Service does not have carrier service data',$service->toArray());
                continue;
            }

            $carrierData = $carrierService->carrier;
            if (is_null($carrierData)) {
                ApiRequestNote::newNote('debug','Carrier Service does not have carrier data',$service->toArray());
                continue;
            }

            if ($carrierData->id === $bannedCarriers->carrier_id) {
                $service->isFiltered = true;
                $service->filteredNote = 'Carrier Country Ban';
                $service->save();

                ApiRequestNote::newNote('debug','Carrier: '.$carrierData->name.' is banned',$service->toArray());
                continue;
            }

            //ApiRequestNote::newNote('debug','Service',$service->toArray());
        }
    }
}
