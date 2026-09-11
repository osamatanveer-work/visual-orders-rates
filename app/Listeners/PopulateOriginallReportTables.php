<?php

namespace App\Listeners;

use App\Events\ShippingQuoteCompleted;
use App\Models\RateQoute;
use App\Models\RateQouteList;
use App\Models\RateQuoteListsTranslation;
use App\Models\RateQuotesTranslation;

class PopulateOriginallReportTables
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ShippingQuoteCompleted $event): void
    {
        //lets tap into the quote completed event and populate the original developers tables

        $rateQoute = new RateQoute;
        $rateQoute->name = 'Rate-Quote ' . $rateQoute->newInstance()->count() + 1;
        $rateQoute->store_id = $event->shippingQuote->store_id;
        $rateQoute->app = $event->shippingQuote->store->slug;
        $rateQoute->address = $event->shippingQuote->shipTo->address1;
        $rateQoute->city = $event->shippingQuote->shipTo->city;
        $rateQoute->province = $event->shippingQuote->shipTo->stateOrProvince;
        $rateQoute->country = $event->shippingQuote->shipTo->countryCode;
        $rateQoute->save();

        $translation = new RateQuotesTranslation;
        $translation->rate_quote_id = $rateQoute->id;
        $translation->quote_id = $event->shippingQuote->id;
        $translation->save();

        $services = $event->shippingQuote->services;
        $deDup = []; //placeholder
        foreach ($services as $service) {
            if ($service->isFiltered === true) {
                continue;
            }

            //if we already logged this service lets skip it
            if (in_array($service->service->service_name, $deDup)) {
                continue;
            } else {
                $deDup[] = $service->service->service_name;
            }

            $list = new RateQouteList;
            $list->rate_qoute_id = $rateQoute->id;
            $list->store_id = $event->shippingQuote->store->id;
            $list->carrier_id = $service->service->carrier->id;
            $list->service_name = $service->service->service_name;
            $list->markup_id = $service->quote->store->company->markup->id;
            $list->qoute_amount = $service->totalCostWithMarkup;
            $list->retail_price = $service->serviceCost;
            $list->profit_margin = $service->totalCostWithMarkup - $service->serviceCost;
            $list->save();

            $translation = new RateQuoteListsTranslation;
            $translation->quote_list_id = $list->id;
            $translation->service_id = $service->id;
            $translation->save();
        }
    }
}
