<?php

namespace App\Services;

use App\Models\Store;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * ShopifyTokenService
 *
 * Supports BOTH Shopify auth styles, because both are live:
 *
 *  1. Legacy custom app - a permanent Admin API access token, issued from the
 *     store admin before 1 January 2026. Shopify retired the ability to CREATE
 *     these; it did not revoke the ones already issued. Existing legacy apps
 *     keep working, so every store already onboarded still has a valid
 *     credential and must not be forced to re-onboard.
 *
 *  2. Dev Dashboard app - Client ID + Client Secret exchanged via the
 *     client_credentials grant for a token that expires in 24 hours
 *     (expires_in = 86399). This is the only path available for new stores.
 *
 * Client credentials take precedence when a store has both, so migrating a
 * store is just a matter of entering the new pair.
 *
 * NOTE: client_credentials only works when the app and the store belong to the
 * same Shopify organization. For stores owned by other merchants you need full
 * OAuth (managed install / token exchange) - not implemented here.
 */
class ShopifyTokenService
{
    /**
     * True when the store is set up as a Dev Dashboard app.
     */
    public function usesClientCredentials(Store $store): bool
    {
        return !empty($store->shopify_client_id) && !empty($store->shopify_client_secret);
    }

    /**
     * True when the store holds a legacy permanent access token and no
     * client credentials to supersede it.
     */
    public function usesLegacyToken(Store $store): bool
    {
        return !$this->usesClientCredentials($store) && !empty($store->access_token);
    }

    /**
     * True when the store can authenticate with Shopify at all, by either
     * method. Callers should check this before attempting a carrier sync.
     */
    public function hasCredentials(Store $store): bool
    {
        return $this->usesClientCredentials($store) || !empty($store->access_token);
    }

    /**
     * Return a valid Admin API access token for the given store.
     */
    public function getToken(Store $store, bool $forceRefresh = false): string
    {
        if (!$this->usesClientCredentials($store)) {
            if (!empty($store->access_token)) {
                // Legacy custom app: the stored token IS the token. There is
                // nothing to fetch, nothing to cache and nothing to refresh.
                return $store->access_token;
            }

            throw new Exception(
                "Store [{$store->id}] has no Shopify credentials. Enter either a legacy " .
                "API access token, or a Client ID and Secret from the Shopify Dev Dashboard."
            );
        }

        if (!$forceRefresh) {
            $cached = Cache::get($this->cacheKey($store));
            if (!empty($cached)) {
                return $cached;
            }
        }

        return $this->refresh($store);
    }

    /**
     * Force a new token from Shopify and cache it.
     *
     * Only meaningful for client_credentials stores - calling this on a legacy
     * store is a programming error, so it throws rather than silently
     * returning the permanent token.
     */
    public function refresh(Store $store): string
    {
        if (!$this->usesClientCredentials($store)) {
            throw new Exception(
                "Store [{$store->id}] uses a legacy permanent access token, which cannot be " .
                "refreshed. If Shopify is rejecting it, the legacy app was deleted or its key " .
                "was revoked - it cannot be reissued, so create a Dev Dashboard app instead."
            );
        }

        $shop = self::shopDomain($store->store_url);

        if (!str_ends_with($shop, '.myshopify.com')) {
            throw new Exception(
                "Store URL must be the permanent .myshopify.com domain (got '{$shop}'). " .
                "The Admin API is not served on custom storefront domains."
            );
        }

        $client = new Client(['timeout' => config('shopify.token_timeout', 10)]);

        $response = $client->post("https://{$shop}/admin/oauth/access_token", [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => $store->shopify_client_id,
                'client_secret' => $store->shopify_client_secret,
            ],
            'http_errors' => false,
        ]);

        $status = $response->getStatusCode();
        $body   = (string) $response->getBody();

        if ($status !== 200) {
            // shop_not_permitted means the app and store are in different orgs.
            Log::error('Shopify token request failed', [
                'store'  => $store->id,
                'shop'   => $shop,
                'status' => $status,
                'body'   => $body,
            ]);

            throw new Exception("Shopify token request failed (HTTP {$status}): {$body}");
        }

        $data = json_decode($body, true);

        if (empty($data['access_token'])) {
            throw new Exception("Shopify token response contained no access_token: {$body}");
        }

        $margin = (int) config('shopify.token_safety_margin', 120);
        $ttl    = max(60, (int) ($data['expires_in'] ?? 86399) - $margin);

        Cache::put($this->cacheKey($store), $data['access_token'], $ttl);

        return $data['access_token'];
    }

    /**
     * Drop the cached token (use after rotating the client secret).
     * Harmless on legacy stores - there is no cache entry to clear.
     */
    public function forget(Store $store): void
    {
        Cache::forget($this->cacheKey($store));
    }

    private function cacheKey(Store $store): string
    {
        return "shopify_access_token_store_{$store->id}";
    }

    /**
     * Normalise whatever is in store_url down to a bare hostname.
     * "https://deed8e-36.myshopify.com/" -> "deed8e-36.myshopify.com"
     */
    public static function shopDomain(?string $storeUrl): string
    {
        $value = trim((string) $storeUrl);
        $host  = parse_url($value, PHP_URL_HOST);

        if (empty($host)) {
            $host = preg_replace('#^https?://#i', '', $value);
            $host = explode('/', (string) $host)[0];
        }

        return strtolower(rtrim((string) $host, '/'));
    }
}