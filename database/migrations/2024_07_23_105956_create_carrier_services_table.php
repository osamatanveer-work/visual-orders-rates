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
        Schema::create('carrier_services', function (Blueprint $table) {
            $table->id();
            $table->string('carrier_name');
            $table->string('service_type');
            $table->string('service_name')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->enum('api_allowed', [1, 0]);
            $table->string('api_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier_services');
    }
};
