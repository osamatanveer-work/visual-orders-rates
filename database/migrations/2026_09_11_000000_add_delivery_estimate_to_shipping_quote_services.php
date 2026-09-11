<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the delivery-estimate columns that the ShippingQuoteService model and the
 * FedEx / UPS providers already write to.
 *
 * WHY THIS FILE EXISTS
 * The delivery-estimate feature was deployed with these four attributes added to
 * app/Models/ShippingQuoteService.php ($fillable + $casts) and written in
 * app/ApiProviders/Fedex.php and app/ApiProviders/UPS.php, but the matching
 * migration was never committed. Any service that received an estimate (every
 * FedEx and UPS rate) then failed to save with "Unknown column 'minDeliveryDate'",
 * so those rates were dropped and the rate-quotes log stopped populating.
 *
 * All columns are nullable — USPS via ShipStation legitimately has no transit
 * data, and any carrier can omit it for a given lane. The hasColumn guards make
 * this safe to run even if a column was added by hand on a server already.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_quote_services', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_quote_services', 'minDeliveryDate')) {
                $table->dateTime('minDeliveryDate')->nullable()->after('filteredNote');
            }
            if (!Schema::hasColumn('shipping_quote_services', 'maxDeliveryDate')) {
                $table->dateTime('maxDeliveryDate')->nullable()->after('minDeliveryDate');
            }
            if (!Schema::hasColumn('shipping_quote_services', 'transitBusinessDays')) {
                $table->integer('transitBusinessDays')->nullable()->after('maxDeliveryDate');
            }
            if (!Schema::hasColumn('shipping_quote_services', 'deliveryEstimateSource')) {
                $table->string('deliveryEstimateSource')->nullable()->after('transitBusinessDays');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_quote_services', function (Blueprint $table) {
            foreach ([
                'minDeliveryDate',
                'maxDeliveryDate',
                'transitBusinessDays',
                'deliveryEstimateSource',
            ] as $column) {
                if (Schema::hasColumn('shipping_quote_services', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
