<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * Authentication modes for a connected store.
     *
     * Referenced by resources/views/companies/show.blade.php (the
     * "Dev Dashboard app" / "Legacy token" badges) and read in
     * StoreController / ShopifyApiService. Without these two constants the
     * company page throws:
     *   "Undefined constant App\Models\Store::AUTH_CLIENT_CREDENTIALS".
     */
    public const AUTH_CLIENT_CREDENTIALS = 'client_credentials';
    public const AUTH_LEGACY_TOKEN       = 'legacy_token';

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'store_url',
        'img',
        'callback_url',
        'access_token',
        'shopify_id',
        'shopify_client_id',
        'shopify_client_secret',
        'auth_mode'
    ];

    public function shopify_ids()
    {
        return $this->hasMany(StoreCarrier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
