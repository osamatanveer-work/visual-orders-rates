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
        Schema::table('boxes', function (Blueprint $table) {
            // Drop 'size' column if it exists
            $table->dropColumn('size');

            // Add new columns with the appropriate data types and nullable constraints
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('package_weight')->nullable();
            $table->float('max_weight')->nullable();
            $table->string('package_code')->nullable(); // Assuming package_code should be a string, change if necessary
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxes', function (Blueprint $table) {
            // Remove the newly added columns in the 'up' method
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('package_weight');
            $table->dropColumn('max_weight');
            $table->dropColumn('package_code');

            // Re-add the 'size' column to revert the changes
            $table->string('size')->nullable();
        });
    }
};
