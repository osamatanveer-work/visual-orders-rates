<?php

namespace App\PublicApi;

use Amp\Future;
use Amp\TimeoutCancellation;
use App\ApiProviders\Fedex;
use App\ApiProviders\IApiProvider;
use App\ApiProviders\ShipStation;
use App\ApiProviders\UPS;
use App\Events\ShippingQuoteCompleted;
use App\Libraries\SendApiError;
use App\Libraries\UnitConversions;
use App\Models\ApiRequestHeader;
use App\Models\ApiRequestNote;
use App\Models\Box;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\ShippingQuoteService;
use App\Models\ShippingQuoteServiceMarkup;
use App\Models\Store;
use App\RemoteStores\IRemoteStore;
use App\RemoteStores\Shopify;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Ubench;
use function Amp\async;

class getRates
{
    public function __construct(
        protected readonly ApiRequestHeader $apiRequestHeader,
        protected readonly string           $slug,
        protected readonly Request          $request
    )
    {
        $this->apiRequestHeader->endpointName = 'getRates';
        $this->apiRequestHeader->save();
    }

    public function dispatch(): array
    {
        //do some base validation
        $this->baseValidation();

        //grab our enabled carriers
        $this->getEnabledCarriers();

        //grab our remote store
        $remoteStore = $this->getRemoteStore();

        //we may want to modify the request before validation
        $remoteStore->preValidationModify($this->request);

        //now validate the remoteStore data
        $remoteStore->validateInput($this->request);

        //we may want to modify the request after validation
        $remoteStore->postValidationModify($this->request);

        //if we don't have a storeData obj in the IOC
        if (!App::bound('storeData')) {
            throw new Exception("Store data not found");
        }

        //if we don't have an apiRequestHeader in the IOC
        if (!App::bound('apiRequestHeader')) {
            throw new Exception("Api request header not found");
        }

        //make our local objects
        $storeData = App::make('storeData');
        $apiRequestHeader = App::make('apiRequestHeader');

        //get the remote store to normalize the input this should return
        //a ShipQuoteHeader object
        $shippingQuote = $remoteStore->normalizeInput($this->request);

        //if the shipping quote already exists in the database we have problems
        if ($shippingQuote->exists) {
            throw new Exception('shipping quote already exists');
        }

        //set more quote attributes
        $shippingQuote->request_id = $apiRequestHeader->id;
        $shippingQuote->store_id = $storeData->id;
        $shippingQuote->orderWeightWithPackagingInGrams = $shippingQuote->orderWeightInGrams;

        //grab our box data
        $box = $this->getBox();

        if (!is_null($box)) {
            //boxes are stored in ounces we need to convert them to grams
            $boxGrams = UnitConversions::ounces_to_grams(floatval($box->package_weight));
            $shippingQuote->orderWeightWithPackagingInGrams += $boxGrams;
        }

        //save our shipping quote
        $shippingQuote->save();

        //bind our shippingQuote to the IOC
        App::instance('shippingQuote', $shippingQuote);

        //kick off a new ubench
        $ubench = new Ubench();

        //start the benchmark
        $ubench->start();
        //gather our promises
        $promises = $this->getPromises();
        $async = Future\awaitAll($promises,new TimeoutCancellation(10));

        //end the benchmark
        $ubench->end();

        ApiRequestNote::newNote('debug', 'async', $async);
        ApiRequestNote::newNote('debug', 'async-ubench', ['ubench' => $ubench->getTime(true)]);

        foreach ($async[0] as $error) {
            if ($error instanceof \Exception) {
                SendApiError::sendErrorEmail($error->getMessage());
                ApiRequestNote::newNote('error', 'async error: ' . $error->getMessage());
            } elseif (is_string($error)) {
                SendApiError::sendErrorEmail($error->getMessage());
                ApiRequestNote::newNote('error', 'async error: ' . $error);
            } else {
                SendApiError::sendErrorEmail('Async Error Unknown Error or Exception');
                ApiRequestNote::newNote('error', 'async error unknown error', [$error]);
            }
        }

        if (count($async[1]) === 0) {
            throw new Exception('the promises did not return data');
        }

        $quotedServices = ShippingQuoteService::ofQuote($shippingQuote->id)->get();
        if (count($quotedServices) > 0) {
            ApiRequestNote::newNote('info', 'quotedServices', $quotedServices->toArray());
        }

        //fire our shipping quote completed event
        ShippingQuoteCompleted::dispatch($shippingQuote);

        //the event fired above may have listeners that changed the data
        //the easiest way is just to query for it again
        $quotedServices = ShippingQuoteService::ofQuote($shippingQuote->id)->get();

        if (count($quotedServices) === 0) {
            throw new \Exception('no quotable services found!');
        }

        //now call the remote store to generate a reply
        return $remoteStore->generateReply($quotedServices);
    }

    /**
     * baseValidation
     * Performs some base validation and binds the storeData object to the container
     *
     * @note This method will either throw an exception or always return true
     * @return bool
     * @throws Exception
     */
    protected function baseValidation(): bool
    {
        $storeData = Store::with([
            'company',
            'company.markup',
            'company.markup.markupcarriers',
            'company.markup.markupcarriers.carrier',
            'company.markup.markupservices',
            'company.markup.markupservices.carrier'
        ])
            ->where('slug', '=', $this->slug)
            ->first();

        if (is_null($storeData)) {
            throw new Exception("Store not found slug: [" . $this->slug . ']');
        }

        ApiRequestNote::newNote('debug', 'storeData', $storeData->toArray());

        $companyData = $storeData->company;
        if (is_null($companyData)) {
            throw new Exception("Company not found");
        }

        $markupHeader = $companyData->markup;
        if (is_null($markupHeader)) {
            throw new Exception("Markup Header not found");
        }

        $markupCarriers = $markupHeader->markupcarriers;
        if (count($markupCarriers) === 0) {
            throw new Exception("Markup Carriers not found");
        }

        $markupServices = $markupHeader->markupservices;

        //since we passed validation on all of the lazy loaded storeData attributes
        //we can safely bind storeData to the container as all of the additional attributes
        //we care about have already been checked, and stored in memory
        App::instance('storeData', $storeData);

        return true;
    }

    /**
     * getEnabledCarriers
     * Returns the enabled carriers for this store. To have a carrier enabled
     * there must be a markup for the carrier
     *
     * @return Collection
     * @throws Exception
     */
    protected function getEnabledCarriers(): Collection
    {
        //if we already have the results of this in the IOC
        //just return them
        if (App::bound('enabledCarriers')) {
            return App::make('enabledCarriers');
        }

        //make sure we have storeData in the IOC
        if (!App::bound('storeData')) {
            throw new Exception('IOC storeData');
        }

        //make the storeData and grab what we want
        $markupCarriers = App::make('storeData')->company->markup->markupcarriers;
        if (count($markupCarriers) === 0) {
            throw new Exception('Markup Carriers not found');
        }

        $enabledCarriers = []; //placeholder
        $tmp = []; //temp array for carrier ids to prevent dups
        foreach ($markupCarriers as $markupCarrier) {
            if (!in_array($markupCarrier->carrier_id, $tmp)) {
                $carrier = $markupCarrier->carrier;

                if (is_null($carrier)) {
                    //lets now throw an exception for this
                    //as "we might" be able to recover
                    //we will let the count later on throw
                    //the exception if there was no carriers
                    continue; //just continue the loop
                }

                $enabledCarriers[] = $carrier;
                $tmp[] = $markupCarrier->carrier_id;
            }
        } //foreach ($markupCarriers as $markupCarrier)

        //if we have no enabled carriers
        if (count($enabledCarriers) === 0) {
            throw new Exception('enabledCarriers not found');
        }

        //ApiRequestNote::newNote('debug', 'enabledCarriers', $enabledCarriers);
        //ApiRequestNote::newNote('debug', 'tmpCarriers', $tmp);

        $return = new Collection($enabledCarriers);

        //bind our enabledCarriers to the life cycle
        App::singleton('enabledCarriers', function () use ($return) {
            return $return;
        });

        //return the data as a collection
        return $return;
    }

    /**
     * getRemoteStore
     * Returns the remote store that is requesting the rate shopper
     * service. This will always be an implementation of IRemoteStore
     *
     * @return IRemoteStore
     * @throws Exception
     */
    protected function getRemoteStore(): IRemoteStore
    {
        //if the remoteStore is already in the container
        //just return in
        if (App::bound('remoteStore')) {
            return App::make('remoteStore');
        }

        //in the future rate shopper will support multiple remote stores
        //however we only support shopify at this point
        $storeType = 'SHOPIFY';
        $classStr = '';

        //determine the remote store and
        //get the IRemoteStore implentation (class name)
        switch (strtoupper(trim($storeType))) {
            case 'SHOPIFY':
                $classStr = Shopify::class;
                break;

            default:
                throw new Exception('unknown store type');
        }

        //if we still don't have a class string throw an exception
        if (strlen($classStr) === 0) {
            throw new Exception('unknown class string');
        }

        //make the IRemoteStore implementation
        $class = App::make($classStr);

        //store this instance in the IOC
        App::instance('remoteStore', $class);

        //return our class
        return $class;
    }

    /**
     * getBox
     * Returns the box data. This is a temp fix as
     * box data will be moved later on
     *
     * @return Box|null
     * @throws Exception
     */
    protected function getBox(): ?Box
    {
        //if we already have the boxData in the IOC return it
        if (App::bound('boxData')) {
            return App::make('boxData');
        }

        //if we don't have storeData in the IOC
        if (!App::bound('storeData')) {
            throw new Exception("Store data not found");
        }
        $storeData = App::make('storeData');

        //check for a box that belongs to this store
        $box = Box::ofStore($storeData->id)->first();

        //if we didn't get a box from the store then
        //try getting the default box
        if (is_null($box)) {
            $box = Box::ofDefaultName()->first();
        }

        if (!is_null($box)) {
            ApiRequestNote::newNote('debug', 'boxData', $box->toArray());
        } else {
            ApiRequestNote::newNote('error', 'no boxData');
        }

        //bind our result to the IOC
        App::instance('boxData', $box);

        return $box;
    }

    /**
     * getPromises
     * This will return the async promises. We use the enabled carriers
     * to determine what API Provider we will use.
     *
     * @return array
     * @throws Exception
     */
    protected function getPromises(): array
    {
        //if we already have this in the IOC just return it
        if (App::bound('promisesData')) {
            return App::make('promisesData');
        }

        //get the enabled carriers
        $enabledCarriers = $this->getEnabledCarriers();

        //if we have no enabledCarriers throw an exception
        if (count($enabledCarriers) === 0) {
            throw new Exception('No enabledCarriers');
        }

        $enabledPromises = [];//placeholder

        //foreach carrier kick off a promise
        foreach ($enabledCarriers as $carrier) {
            $enabledPromises[] = $this->buildPromise($carrier);
        }

        //if we have no enabledPromises throw an exception
        if (count($enabledPromises) === 0) {
            throw new Exception('no enabledPromises');
        }

        //bind our promises to the IOC
        App::instance('promisesData', $enabledPromises);

        //return our promises
        return $enabledPromises;
    }

    /**
     * buildPromise
     * This method is a workhorse. This method creates the
     * async promise. The async promise contains a ton
     * of logic for querying api providers, validating the results,
     * storing the results, and other provisioning features
     *
     * @param Carrier $carrier
     * @return Future
     */
    protected function buildPromise(Carrier $carrier): Future
    {
        //create the async promise
        return async(function () use ($carrier) {
            #ApiRequestNote::newNote('info', 'Starting Promise: ' . $carrier->name);

            //if we don't have a shipping quote throw an exception
            if (!App::bound('shippingQuote')) {
                throw new Exception('IOC shippingQuote');
            }

            //if we don't have storedata throw an exception
            if (!App::bound('storeData')) {
                throw new Exception('IOC storeData');
            }

            $storeData = App::make('storeData');
            $shippingQuote = App::make('shippingQuote');

            //get the api provider (fedex, stamps.com, shipstation, ups, etc)
            $apiProvider = $this->promiseGetApiProvider($carrier->name);

            //we want to get the rates from the api provider
            $rates = $apiProvider->getRates($shippingQuote);

            //if we have no rates throw an exception
            if (count($rates) === 0) {
                throw new Exception('apiProvider did not return any rates');
            }

            //now we want to normalize these rates
            $normalizedRates = $apiProvider->normalizeRates($rates);

            //if we don't have any normalized rates throw an exception
            if (count($normalizedRates) === 0) {
                throw new Exception('apiProvider did not return normalized rates');
            }

            //iterate our normalized rates
            foreach ($normalizedRates as $quotedService) {
                //if information was already written throw an exception
                if ($quotedService->exists) {
                    throw new Exception('The quotedService already exists in the database');
                }

                //update the quote_id
                $quotedService->quote_id = $shippingQuote->id;

                //lets also see if we can find an existing match?
                $serviceMatch = CarrierService::where('service_name', '=', $quotedService->serviceName)
                    ->where('carrier_name', '=', $apiProvider->getCarrierName())
                    //->where('code','=',$service->serviceCode)
                    ->first();

                if (!is_null($serviceMatch)) {
                    //this means we have a service listed in the db that matches
                    $quotedService->service_id = $serviceMatch->id;

                    //if the apiAllowedAsBool is true
                    if ($serviceMatch->apiAllowedAsBool === true) {
                        //then we need to "unfilter" this quote service
                        $quotedService->isFiltered = false;
                        $quotedService->filteredNote = null;
                    } else {
                        //if the api_allowed is false lets update the note
                        $quotedService->filteredNote = 'api_allowed = false';
                    }
                } else {
                    //if we are in local lets create the services
                    //automatically so the developer gets a visual on what is
                    //going on
                    //if (App::environment('local')) {
                    $serviceModel = App::make(CarrierService::class);
                    $serviceModel->carrier_name = $apiProvider->getCarrierName();
                    $serviceModel->service_type = 'Unknown';
                    $serviceModel->service_name = $quotedService->serviceName;
                    $serviceModel->code = $quotedService->serviceCode;
                    $serviceModel->description = 'Auto added in local by API call';
                    $serviceModel->api_allowed = '0';
                    $serviceModel->save();
                    //}
                }

                $quotedService->save();

                //if the quoted service is filtered just continue
                if ($quotedService->isFiltered === true) {
                    continue;
                }

                //now lets grab the markups that we need
                $markupCarriers = $storeData->company->markup->markupcarriers;

                //if we have no markupcarriers throw an exception
                if (count($markupCarriers) === 0) {
                    throw new Exception('markupcarriers not found');
                }

                $executeMarkups = []; //placeholder

                $enabledMarkupCarriers = []; //placeholder
                //iterate the carrier markups
                foreach ($markupCarriers as $markup) {
                    //if the markup carrier id does not match our current carrier id continue
                    if ($markup->carrier_id !== $carrier->id) {
                        continue;
                    }

                    $countries = $markup->countriesAsDecoded;
                    $shipToCountryCode = strtolower($shippingQuote->shipTo->countryCode);

                    //if we have no countries it means this rule is universal
                    if (count($countries) === 0) {
                        $enabledMarkupCarriers[] = $markup;
                        continue;
                    }

                    //if the destination country is in the countries flag
                    //it means we want this rule
                    if (in_array($shipToCountryCode, $countries)) {
                        $enabledMarkupCarriers[] = $markup;
                        continue;
                    }
                }

                //null out the old markup
                $markup = null;

                //if we have no enabled markups it will be a serious problem
                if (count($enabledMarkupCarriers) === 0) {
                    throw new Exception('no enabledMarkup Carriers');
                }

                //iterate through our markups and add them to the table
                foreach ($enabledMarkupCarriers as $markup) {
                    //we don't want nulls set to 0
                    if (is_null($markup->markup_fixed)) {
                        $markup->markup_fixed = 0;
                    }

                    //we don't want nulls set to zero
                    if (is_null($markup->markup_percent)) {
                        $markup->markup_percent = 0;
                    }

                    //cast the values to floats
                    $markup->markup_fixed = floatval($markup->markup_fixed);
                    $markup->markup_percent = floatval($markup->markup_percent);

                    //carrier markups can be both fixed and percent
                    if ($markup->markup_fixed > 0) {
                        $markupModel = App::make(ShippingQuoteServiceMarkup::class);
                        $markupModel->service_id = $quotedService->id;
                        $markupModel->markup_id = $markup->id;
                        $markupModel->markupOrigin = 'CARRIER';
                        $markupModel->markupType = 'FIXED';
                        $markupModel->amount = $markup->markup_fixed;
                        $markupModel->save();

                        $executeMarkups[] = $markupModel;
                    }

                    if ($markup->markup_percent > 0) {
                        $markupModel = App::make(ShippingQuoteServiceMarkup::class);
                        $markupModel->service_id = $quotedService->id;
                        $markupModel->markup_id = $markup->id;
                        $markupModel->markupOrigin = 'CARRIER';
                        $markupModel->markupType = 'PERCENT';
                        $markupModel->amount = $markup->markup_percent;
                        $markupModel->save();

                        $executeMarkups[] = $markupModel;
                    }
                }

                //nullify the markup
                $markup = null;

                //grab our markup services
                $markupServices = $storeData->company->markup->markupservices;
                $enabledMarkupServices = []; //placeholder

                //iterate our markup services
                foreach ($markupServices as $markup) {
                    //if the carrier_id is not equal to the current carrier id
                    // just continue onward
                    if ($markup->carrier_id !== $carrier->id) {
                        continue;
                    }

                    //if the quoted service_id is not in the decoded service ids
                    //then just continue onward
                    if (!in_array($quotedService->service_id, $markup->servicesDecoded)) {
                        continue;
                    }

                    $countries = $markup->countriesDecoded;
                    $shipToCountryCode = strtolower($shippingQuote->shipTo->countryCode);

                    //if there are no countries it means this rule is universal
                    if (count($countries) === 0) {
                        //this means the markup is global
                        $enabledMarkupServices[] = $markup;
                        continue;
                    }

                    //if the ship to country code is in the countries array
                    //it means we matched the country flag
                    if (in_array($shipToCountryCode, $countries)) {
                        $enabledMarkupServices[] = $markup;
                    }
                }

                //nullify markup
                $markup = null;

                //iterate our enabledMarkup services
                foreach ($enabledMarkupServices as $markup) {
                    $markupModel = App::make(ShippingQuoteServiceMarkup::class);
                    $markupModel->service_id = $quotedService->id;
                    $markupModel->markup_id = $markup->id;
                    $markupModel->markupOrigin = 'SERVICE';

                    if ($markup->markup_type === 'percent') {
                        $markupModel->markupType = 'PERCENT';
                    } elseif ($markup->markup_type === 'fixed') {
                        $markupModel->markupType = 'FIXED';
                    } else {
                        continue;
                    }

                    $markupModel->amount = $markup->amount;
                    $markupModel->save();
                    $executeMarkups[] = $markupModel;
                }

                //nullify markup
                $markup = null;

                $currentCost = $quotedService->serviceCost;
                //now we can execute these markups
                foreach ($executeMarkups as $markup) {
                    $markup->startingCost = $currentCost;

                    switch (strtoupper(trim($markup->markupType))) {
                        case 'FIXED':
                            $markup->endingCost = $markup->startingCost + $markup->amount;
                            $markup->formula = $markup->startingCost . ' + ' . $markup->amount;
                            break;

                        case 'PERCENT':
                            $markup->endingCost = $markup->startingCost + (($markup->amount / 100) * $markup->startingCost);
                            $markup->formula = $markup->startingCost . ' + ((' . $markup->amount . ' / 100) * ' . $markup->startingCost . ')';
                            break;

                        default:
                            throw new Exception('markupType not supported');
                    }

                    $markup->save();
                    $currentCost = $markup->endingCost;
                }

                $quotedService->totalCostWithMarkup = round($currentCost, 2, PHP_ROUND_HALF_UP);
                $quotedService->markupCost = round($quotedService->totalCostWithMarkup - $quotedService->serviceCost, 2, PHP_ROUND_HALF_UP);
                $quotedService->save();
            }

            #ApiRequestNote::newNote('info', 'Ending Promise: ' . $carrier->name);
        });
    }

    /**
     * promiseGetApiProvider
     * The is part of the buildPromise routine. This
     * method will get the api provider class (an implementation of IApiProvider)
     *
     * @param string $apiProviderName
     * @return IApiProvider
     * @throws Exception
     */
    protected function promiseGetApiProvider(string $apiProviderName): IApiProvider
    {
        $apiProviderClassStr = '';

        //determine what api provider we should return
        switch (strtoupper(trim($apiProviderName))) {
            case 'USPS':
                $apiProviderClassStr = ShipStation::class;
                break;

            case 'FEDEX':
                $apiProviderClassStr = Fedex::class;
                break;

            case 'UPS':
                $apiProviderClassStr = UPS::class;
                break;

            default:
                throw new Exception('unknown provider');
        }

        //if our class string is still empty throw an exception
        if (strlen($apiProviderClassStr) === 0) {
            throw new Exception('unknown class string');
        }

        //return our object - the IApiProvider on this method will enforce contract
        return App::make($apiProviderClassStr);
    }
}
