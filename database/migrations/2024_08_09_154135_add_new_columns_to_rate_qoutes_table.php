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
        Schema::table('rate_qoutes', function (Blueprint $table) {
            $table->text('store_name')->nullable();
            $table->text('app')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rate_qoutes', function (Blueprint $table) {
            $table->dropColumn(['app', 'address', 'city', 'province', 'country']);
        });
    }
};
