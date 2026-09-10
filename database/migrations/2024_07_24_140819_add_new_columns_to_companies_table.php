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
        Schema::table('companies', function (Blueprint $table) {
            // Add the foreign key column and define the foreign key constraint
            $table->unsignedBigInteger('markup_id');
            $table->foreign('markup_id')->references('id')->on('markups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Drop the foreign key constraint before dropping the column
            $table->dropForeign(['markup_id']);
            $table->dropColumn('markup_id');
        });
    }
};
