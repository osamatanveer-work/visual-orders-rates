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
        Schema::create('api_request_notes', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('level');
            $table->string('message');
            $table->json('data')->nullable();

            $table->timestamps();

            $table->index(['request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_notes');
    }
};
