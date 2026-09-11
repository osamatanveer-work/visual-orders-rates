<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Markup;
use App\Models\MarkupCarrier;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WilliamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //CarrierService::where('carrier_name','<>','UPS')->delete();

        $markup = new Markup;
        $check = $markup->newInstance()
            ->where('slug', '=', Str::slug('DEV - TEST'))
            ->first();

        if (is_null($check)) {
            $markup->name = 'DEV - TEST';
            $markup->slug = Str::slug('DEV - TEST');
            $markup->save();
        } else {
            $markup->id = $check->id;
        }

        $company = new Company;
        $check = $company->newInstance()
            ->where('slug', '=', Str::slug('Test Company'))
            ->first();

        if (is_null($check)) {
            $company->name = 'Test Company';
            $company->slug = Str::slug('Test Company');
            $company->description = 'Dev Test Company';
            $company->markup_id = $markup->id;
            $company->save();
        } else {
            $company->id = $check->id;
        }

        $store = new Store;
        $check = $store->newInstance()
            ->where('slug', '=', trim(Str::slug('0920 Test Store')))
            ->first();

        if (is_null($check)) {
            $store->company_id = $company->id;
            $store->name = '0920 Test Store';
            $store->slug = Str::slug('0920 Test Store');
            $store->store_url = 'http://laravel.test';
            $store->access_token = 'abc123';
            $store->save();
        }

        $markupCarrier = new MarkupCarrier;

        $carriers = [1, 2, 3];
        foreach ($carriers as $carrier) {
            $check = $markupCarrier->newInstance()->where('markup_id', '=', $markup->id)
                ->where('carrier_id', '=', $carrier)
                ->first();

            if (is_null($check)) {
                $mC = $markupCarrier->newInstance();
                $mC->markup_id = $markup->id;
                $mC->carrier_id = $carrier;
                $mC->markup_scope = 'per_order';
                $mC->markup_fixed = 1;
                $mC->save();
            }
        }
    }
}
