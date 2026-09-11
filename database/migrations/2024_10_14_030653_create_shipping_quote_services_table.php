<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_quote_services', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('quote_id')->unsigned();
            $table->bigInteger('service_id')->unsigned()->nullable();
            $table->string('serviceName');
            $table->string('serviceCode');
            $table->float('serviceCost');
            $table->float('markupCost')->nullable();
            $table->float('totalCostWithMarkup')->nullable();
            $table->boolean('isFiltered')->default(true);
            $table->string('filteredNote')->default('Default Filter')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_quote_services');
    }
};
