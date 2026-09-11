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
        Schema::table('markup_carriers', function (Blueprint $table) {
            $table->dropColumn('markup_type');
            $table->dropColumn('amount');
            $table->float('markup_fixed')->nullable();
            $table->float('markup_percent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('markup_carriers', function (Blueprint $table) {
            $table->enum('markup_type', ['percent', 'fixed']);
            $table->float('amount');
            $table->dropColumn([
                'markup_fixed',
                'markup_percent'
            ]);
        });
    }
};
