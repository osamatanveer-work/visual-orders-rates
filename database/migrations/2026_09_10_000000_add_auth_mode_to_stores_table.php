<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the `auth_mode` column the badge UI expects.
 *
 * OPTIONAL for fixing the crash (the two constants on the Store model are what
 * stop the 500). This column is only needed if you want the "Dev Dashboard app"
 * / "Legacy token" badge to actually reflect each store's auth type. Without it,
 * auth_mode reads as null and the view shows its neutral/else badge.
 *
 * Defaults existing rows to client_credentials, since every store connected
 * under the new flow uses that.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'auth_mode')) {
                $table->string('auth_mode')->default('client_credentials')->after('shopify_client_secret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('auth_mode');
        });
    }
};
