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
        Schema::table('rate_qoute_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('rate_qoute_id');
            $table->foreign('rate_qoute_id')->references('id')->on('rate_qoutes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rate_qoute_lists', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['rate_qoute_id']); // Drop foreign key using array notation
            $table->dropColumn('rate_qoute_id'); // Then drop the column itself
        });
    }
};
