<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the Dev Dashboard client credentials to the stores table.
 *
 * Under the new Shopify auth model these two values replace the old permanent
 * access_token. The access_token column is left in place so nothing breaks, but
 * it is no longer the source of truth - tokens are fetched on demand and cached.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'shopify_client_id')) {
                $table->string('shopify_client_id')->nullable()->after('store_url');
            }
            if (!Schema::hasColumn('stores', 'shopify_client_secret')) {
                $table->text('shopify_client_secret')->nullable()->after('shopify_client_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['shopify_client_id', 'shopify_client_secret']);
        });
    }
};
