<?php

namespace App\RemoteStores;

use App\Models\Address;
use App\Models\ApiRequestNote;
use App\Models\ShippingQuoteHeader;
use App\Models\CarrierService;
use App\Support\TransitEstimate;
use DateTime;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Shopify implements IRemoteStore
{
    /**
     * getRemoteStoreType
     * Returns the remote store type as a string
     * ie. magento, shopify, woocommerce
     *
     * @return string
     */
    public function getRemoteStoreType(): string
    {
        return 'shopify';
    }

    /**
     * preValidationModify
     * The method is built to allow developers to modify the
     * request object before validation routines are run
     *
     * @param Request $request
     *
     * @return void
     */
    public function preValidationModify(Request $request): void
    {
    }

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
    public function validateInput(Request $request): bool
    {
        $keysAndSubkeys = [
            'rate',
            'rate.origin',
            'rate.destination',
            'rate.items'
        ];

        $data = new Data($request->all());

        foreach ($keysAndSubkeys as $check) {
            if (!$data->has($check)) {
                throw new Exception('input validation: ' . $check);
            }
        }

        return true;
    }

    /**
     * postValidationModify
     * This method allows the developer to modify the request data
     * after validation occurs
     *
     * @param Request $request
     *
     * @return void
     */
    public function postValidationModify(Request $request): void
    {
    }

    /**
     * normalizeInput
     * This method is responsible for constructing a ShippingQuoteHeader
     *
     * @param Request $request
     *
     * @return ShippingQuoteHeader
     */
    public function normalizeInput(Request $request): ShippingQuoteHeader
    {
        //since validation has occurred we can safely use items from the request
        $destinationAddress = $request->get('rate')['destination'];
        $originAddress = $request->get('rate')['origin'];

        //get the destination address
        //
        //address1/city/stateOrProvince/postalCode/countryCode are all NOT
        //NULL in the addresses table, but Shopify sends partial addresses
        //(e.g. a cart-page rate estimator that only collects zip/province/
        //country before checkout) with several of these as null - without
        //these fallbacks the insert throws, the whole request 500s, and
        //Shopify silently falls back to its own generic backup rate instead
        //of ours.
        $address = App::make(Address::class);
        $address->address1 = $destinationAddress['address1'] ?? '';
        $address->address2 = $destinationAddress['address2'];
        $address->address3 = $destinationAddress['address3'];
        $address->city = $destinationAddress['city'] ?? '';
        $address->stateOrProvince = $destinationAddress['province'] ?? '';
        $address->postalCode = $destinationAddress['postal_code'] ?? '';
        $address->countryCode = $destinationAddress['country'] ?? '';
        $shipTo = Address::findOrCreateByFingerprint($address);

        //get the origin address
        $address = App::make(Address::class);
        $address->address1 = $originAddress['address1'] ?? '';
        $address->address2 = $originAddress['address2'];
        $address->address3 = $originAddress['address3'];
        $address->city = $originAddress['city'] ?? '';
        $address->stateOrProvince = $originAddress['province'] ?? '';
        $address->postalCode = $originAddress['postal_code'] ?? '';
        $address->countryCode = $originAddress['country'] ?? '';
        $shipFrom = Address::findOrCreateByFingerprint($address);

        $shipToName = $request->get('rate')['destination']['name'];
        $shipFromName = $request->get('rate')['origin']['name'];

        //get the order weight
        $orderWeightInGrams = 0;

        $items = $request->get('rate')['items'];
        foreach ($items as $item) {
            if ($item['requires_shipping'] === false) {
                continue;
            }

            if (isset($item['quantity'])) {
                $orderWeightInGrams += $item['grams']*$item['quantity'];
            } else {
                $orderWeightInGrams += $item['grams'];
            }
        }

        //create a new ShippingQuoteHeader
        //we create a new model but we don't save it
        //so that missing data can be supplemented by the
        //calling logic
        $header = App::make(ShippingQuoteHeader::class);
        $header->shipto_id = $shipTo->id;
        $header->shiptoName = $shipToName;
        $header->shipfrom_id = $shipFrom->id;
        $header->shipfromName = $shipFromName;
        $header->orderWeightInGrams = $orderWeightInGrams;

        return $header;
    }

    /**
     * generateReply
     * This method is responsible for assembling the reply to
     * the remote store.
     *
     * @param Collection $services
     *
     * @return array
     */
    public function generateReply(Collection $services): array
    {
        $return = [
            'rates' => []
        ];

        foreach ($services as $service) {
            $rate = [
                'service_name' => $service->serviceName,
                'service_code' => $service->serviceCode,
                'total_price' => round(($service->totalCostWithMarkup*100),2,PHP_ROUND_HALF_UP),
                'currency' => 'USD'
            ];

            //delivery estimates are per-service and come from the carrier.
            //
            //this used to be one hardcoded +1day/+7days window, computed once
            //outside this loop and stamped onto every rate - so Next Day Air
            //and Ground both advertised the same arrival at checkout.
            //
            //both fields are OPTIONAL in Shopify's carrier service response.
            //when a carrier gives us nothing (USPS via ShipStation never does)
            //we omit the keys rather than invent a window.
            $minDeliveryDate = $service->minDeliveryDate;
            $maxDeliveryDate = $service->maxDeliveryDate ?? $minDeliveryDate;

            if (!is_null($minDeliveryDate)) {
                $rate['min_delivery_date'] = $minDeliveryDate->format(TransitEstimate::SHOPIFY_FORMAT);
            }

            if (!is_null($maxDeliveryDate)) {
                $rate['max_delivery_date'] = $maxDeliveryDate->format(TransitEstimate::SHOPIFY_FORMAT);
            }

            $return['rates'][] = $rate;
        }

        //lets dedup the rates
        $dedupKeys = [];
        $newReturn = [
            'rates' => []
        ];

        //cache the service name lookups so we don't hit the database once per
        //rate inside the loop below
        $apiNames = [];

        foreach ($return['rates'] as $rate) {
            //fingerprint on identity and price only. delivery dates are
            //deliberately excluded: two genuinely duplicate quotes should still
            //collapse even if one carried an estimate and the other did not.
            $fingerprint = hash('sha256',json_encode([
                $rate['service_name'],
                $rate['service_code'],
                $rate['total_price']
            ]));

            if (in_array($fingerprint,$dedupKeys)) {
                //ApiRequestNote::newNote('debug','Removing duplicate response',$rate);
                continue;
            }

            if (!array_key_exists($rate['service_name'],$apiNames)) {
                $apiNames[$rate['service_name']] = null;

                $serviceData = CarrierService::where('service_name','=',$rate['service_name'])
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();

                foreach ($serviceData as $svc) {
                    if (!empty($svc->api_name)) {
                        $apiNames[$rate['service_name']] = $svc->api_name;
                    }
                }
            }

            if (!is_null($apiNames[$rate['service_name']])) {
                $rate['service_name'] = $apiNames[$rate['service_name']];
            }

            $dedupKeys[] = $fingerprint;
            $newReturn['rates'][] = $rate;
        }

        return $newReturn;
    }
}