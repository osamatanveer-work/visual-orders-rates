<?php

namespace App\Console\Commands\SoftwareAdmin;

use App\Models\Carrier;
use App\Models\Store;
use App\Models\StoreBannedCarrier;
use Illuminate\Console\Command;

class CarrierCountryBan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:carrier-country-ban {store_id? : Store ID} {carrier_id? : Carrier ID} {countryCode? : Country Code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ban a country from having a specific carrier';

    /**
     * Execute the console command.
     */
    public function handle(Store $store, Carrier $carrier, StoreBannedCarrier $storeBannedCarrier)
    {
        $storeID = $this->argument('store_id');
        if (is_null($storeID)) {
            $storeID = $this->ask('Store ID');
        }

        $carrierID = $this->argument('carrier_id');
        if (is_null($carrierID)) {
            $carrierID = $this->ask('Carrier ID');
        }

        $countryCode = $this->argument('countryCode');
        if (is_null($countryCode)) {
            $countryCode = $this->ask('Country Code');
        }
        $countryCode = strtoupper(trim($countryCode));

        //locate the store
        $storeData = $store->find($storeID);

        //if we don't have store data
        if (is_null($storeData)) {
            $this->error('Unable to locate a store for ID: '.$storeID);
            return -1;
        }

        //locate the carrier
        $carrierData = $carrier->find($carrierID);

        if (is_null($carrierData)) {
            $this->error('Unable to locate carrier for ID: '.$carrierID);
            return -1;
        }

        //locate the storeBan
        $storeBan = $storeBannedCarrier->where('store_id','=',$storeID)
            ->where('carrier_id','=',$carrierID)
            ->first();

        if (is_null($storeBan)) {
            //this means we don't have an existing ban
            $ban = $storeBannedCarrier->newInstance();
            $ban->store_id = $storeID;
            $ban->carrier_id = $carrierID;
            $ban->countries = [$countryCode];
            $ban->save();

            $this->comment('Successfully Added Country: '.$countryCode.' to the carrier ban: '.$carrierData->name.' and store: '.$storeID);
            return 0;
        } else {
            //this means a ban already exists
            if (in_array($countryCode, $storeBan->countries)) {
                $this->error('The specified country code '.$countryCode.' is already banned.');
                return -1;
            }

            //this means we have to append this country
            $storeBan->countries[] = $countryCode;
            $storeBan->save();
            $this->comment('Successfully Appended Country: '.$countryCode.' to the carrier ban: '.$carrierData->name.' and store: '.$storeID);
            return 0;
        }
    }
}
