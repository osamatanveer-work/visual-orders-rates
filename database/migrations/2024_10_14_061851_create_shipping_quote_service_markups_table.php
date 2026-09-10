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
        Schema::create('shipping_quote_service_markups', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('service_id')->unsigned();
            $table->bigInteger('markup_id')->unsigned();
            $table->string('markupOrigin'); //carrier, service
            $table->string('markupType'); //fixed, percent
            $table->float('amount');
            $table->string('note')->nullable();

            $table->float('startingCost')->nullable();
            $table->float('endingCost')->nullable();
            $table->string('formula')->nullable();

            $table->timestamps();

            $table->index(['service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_quote_service_markups');
    }
};
