<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * Labels for the two ways a store can authenticate with Shopify.
     * Mirrors the detection logic in ShopifyTokenService::usesClientCredentials()
     * / usesLegacyToken() - kept as a small duplicated check in the accessor
     * below rather than injecting the service into the model.
     */
    public const AUTH_CLIENT_CREDENTIALS = 'client_credentials';
    public const AUTH_LEGACY_TOKEN = 'legacy_token';

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
        'shopify_client_secret'
    ];

    /**
     * Computed, not persisted: reflects whichever credentials are currently
     * set rather than a stored value that could drift out of sync.
     */
    public function getAuthModeAttribute(): ?string
    {
        if (!empty($this->shopify_client_id) && !empty($this->shopify_client_secret)) {
            return self::AUTH_CLIENT_CREDENTIALS;
        }

        if (!empty($this->access_token)) {
            return self::AUTH_LEGACY_TOKEN;
        }

        return null;
    }

    public function shopify_ids()
    {
        return $this->hasMany(StoreCarrier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
