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
        Schema::create('shipping_quote_headers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('shipto_id');
            $table->string('shipToName')->nullable();
            $table->bigInteger('shipfrom_id');
            $table->string('shipFromName')->nullable();
            $table->float('orderWeightInGrams');
            $table->float('orderWeightWithPackagingInGrams')->nullable();

            $table->timestamps();

            $table->index(['request_id', 'store_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_quote_headers');
    }
};
