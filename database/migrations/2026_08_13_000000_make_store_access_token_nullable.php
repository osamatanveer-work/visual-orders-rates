<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * stores.access_token was created as TEXT NOT NULL with no default
 * (2024_08_01_121247_add_new_column_to_stores_table.php).
 *
 * That was fine when every store had a permanent token. Under the Dev Dashboard
 * flow a store may legitimately have no token at all, and on a MySQL server in
 * strict mode (the default since 5.7) the INSERT then fails outright with
 * "Field 'access_token' doesn't have a default value" - meaning NO new store
 * can be created. Non-strict servers hide it by silently writing an empty
 * string, which is arguably worse.
 *
 * Raw DDL is used rather than ->change() so this runs without doctrine/dbal.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('stores', 'access_token')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `stores` MODIFY `access_token` TEXT NULL');
        }

        // Normalise the empty strings a non-strict server may have written, so
        // that "has a legacy token" is a simple NULL check everywhere.
        DB::table('stores')->where('access_token', '')->update(['access_token' => null]);
    }

    public function down(): void
    {
        if (!Schema::hasColumn('stores', 'access_token')) {
            return;
        }

        DB::table('stores')->whereNull('access_token')->update(['access_token' => '']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `stores` MODIFY `access_token` TEXT NOT NULL');
        }
    }
};