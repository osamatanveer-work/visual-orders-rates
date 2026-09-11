<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rate_qoute_lists', function (Blueprint $table) {
            DB::statement('ALTER TABLE rate_qoute_lists MODIFY qoute_amount FLOAT');
            DB::statement('ALTER TABLE rate_qoute_lists MODIFY profit_margin FLOAT');
            DB::statement('ALTER TABLE rate_qoute_lists MODIFY retail_price FLOAT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rate_qoute_lists', function (Blueprint $table) {

        });
    }
};
