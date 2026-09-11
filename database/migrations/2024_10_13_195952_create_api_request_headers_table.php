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
        Schema::create('api_request_headers', function (Blueprint $table) {
            $table->id();

            $table->string('endpointName')->nullable();
            $table->boolean('isTest')->default(true);
            $table->boolean('isSuccess')->default(false);
            $table->json('input')->nullable();
            $table->json('output')->nullable();
            $table->json('additionalData')->nullable();

            $table->timestamps();

            $table->index(['endpointName']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_headers');
    }
};
