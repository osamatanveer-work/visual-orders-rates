<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rate_qoute_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('carrier_id');
            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('cascade');
            $table->text('service_name');
            $table->unsignedBigInteger('markup_id');
            $table->foreign('markup_id')->references('id')->on('markups')->onDelete('cascade');
            $table->float('qoute_amount');
            $table->float('retail_price');
            $table->float('profit_margin');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_qoute_lists');
    }
};
