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
        Schema::create('rate_quote_lists_translations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('quote_list_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();

            $table->timestamps();

            $table->index(['quote_list_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_quote_lists_translations');
    }
};
