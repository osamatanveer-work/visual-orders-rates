<?php

namespace App\Providers;

use App\Events\ShippingQuoteCompleted;
use App\Listeners\PopulateOriginallReportTables;
use App\Listeners\RemoveBannedCountryCarriers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Event::listen(
            ShippingQuoteCompleted::class,
            PopulateOriginallReportTables::class
        );

        Event::listen(
            ShippingQuoteCompleted::class,
            RemoveBannedCountryCarriers::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */


    public function boot()
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        Paginator::useBootstrap();
    }
}
