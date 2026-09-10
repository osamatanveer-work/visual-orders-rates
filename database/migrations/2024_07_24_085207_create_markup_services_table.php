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
        Schema::create('markup_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('markup_id');
            $table->foreign('markup_id')->references('id')->on('markups')->onDelete('cascade');
            $table->unsignedBigInteger('carrier_id');
            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('cascade');
            $table->json('services');
            $table->float('amount');
            $table->enum('markup_type', ['percent', 'fixed']);
            $table->json('countries')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markup_services');
    }
};
