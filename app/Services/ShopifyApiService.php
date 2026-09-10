<?php

namespace App\Services;

use App\Models\Store;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * ShopifyApiService
 *
 * Talks to the Shopify Admin API on behalf of a Store, using whichever
 * credential style that store is configured with (legacy permanent token or
 * Dev Dashboard client credentials). ShopifyTokenService hides the difference.
 *
 *  - Always targets the permanent .myshopify.com domain. The Admin API is not
 *    served on custom storefront domains.
 *  - API version comes from config('shopify.api_version'), NOT a runtime env()
 *    call, so `php artisan config:cache` does not blank it.
 *  - Throws real exceptions rather than returning redirects from a service
 *    class, so callers can roll back properly.
 */
class ShopifyApiService
{
    protected ShopifyTokenService $tokens;

    public function __construct(ShopifyTokenService $tokens)
    {
        $this->tokens = $tokens;
    }

    private function apiVersion(): string
    {
        return config('shopify.api_version', '2025-10');
    }

    private function carrierName(): string
    {
        return config('shopify.carrier_name', 'Rate Shopper Carriers');
    }

    private function request(Store $store, string $method, string $path, ?array $body = null): array
    {
        $shop = ShopifyTokenService::shopDomain($store->store_url);

        if (!str_ends_with($shop, '.myshopify.com')) {
            throw new Exception(
                "Store [{$store->id}] URL '{$shop}' is not a .myshopify.com domain. " .
                "The Admin API is only served on the permanent shop domain."
            );
        }

        $client = new Client(['timeout' => config('shopify.timeout', 15)]);
        $url    = "https://{$shop}/admin/api/{$this->apiVersion()}{$path}";

        $options = [
            'headers' => [
                'Content-Type'           => 'application/json',
                'X-Shopify-Access-Token' => $this->tokens->getToken($store),
            ],
            'http_errors' => false,
        ];

        if ($body !== null) {
            $options['json'] = $body;
        }

        $response = $client->request($method, $url, $options);
        $status   = $response->getStatusCode();
        $raw      = (string) $response->getBody();

        // A 401 on a client_credentials store can mean the cached token was
        // revoked early - mint a fresh one and retry once.
        //
        // On a LEGACY store there is nothing to refresh: the permanent token is
        // the only credential there is, and calling refresh() would throw and
        // mask the real 401. So skip the retry and let the error surface.
        if ($status === 401 && $this->tokens->usesClientCredentials($store)) {
            $options['headers']['X-Shopify-Access-Token'] = $this->tokens->getToken($store, true);

            $response = $client->request($method, $url, $options);
            $status   = $response->getStatusCode();
            $raw      = (string) $response->getBody();
        }

        if ($status < 200 || $status >= 300) {
            Log::error('Shopify API error', [
                'store'  => $store->id,
                'mode'   => $store->auth_mode,
                'url'    => $url,
                'status' => $status,
                'body'   => $raw,
            ]);

            if ($status === 401) {
                throw new Exception(
                    "Shopify rejected the credentials for store [{$store->id}] (HTTP 401). " .
                    ($this->tokens->usesLegacyToken($store)
                        ? "This store still uses a legacy access token - it may have been revoked. Create a Dev Dashboard app and enter the Client ID and Secret."
                        : "Check the Client ID and Secret in the Dev Dashboard.")
                );
            }

            throw new Exception("Shopify API {$method} {$path} failed (HTTP {$status}): {$raw}");
        }

        return json_decode($raw, true) ?? [];
    }

    /**
     * Register this store's callback as a carrier service.
     * Returns the Shopify carrier_service id.
     */
    public function createCarrier(Store $store): int
    {
        if (empty($store->callback_url)) {
            throw new Exception("Store [{$store->id}] has no callback_url to register.");
        }

        $result = $this->request($store, 'POST', '/carrier_services.json', [
            'carrier_service' => [
                'name'              => $this->carrierName(),
                'callback_url'      => $store->callback_url,
                'service_discovery' => true,
                'active'            => true,
            ],
        ]);

        if (empty($result['carrier_service']['id'])) {
            throw new Exception('Shopify did not return a carrier_service id: ' . json_encode($result));
        }

        return (int) $result['carrier_service']['id'];
    }

    /**
     * List carrier services currently registered on the store.
     */
    public function listCarriers(Store $store): array
    {
        $result = $this->request($store, 'GET', '/carrier_services.json');

        return $result['carrier_services'] ?? [];
    }

    /**
     * Update an existing carrier service (e.g. after the callback URL changes).
     */
    public function updateCarrier(Store $store): array
    {
        if (empty($store->shopify_id)) {
            throw new Exception("Store [{$store->id}] has no shopify_id to update.");
        }

        return $this->request($store, 'PUT', "/carrier_services/{$store->shopify_id}.json", [
            'carrier_service' => [
                'id'           => $store->shopify_id,
                'name'         => $this->carrierName(),
                'callback_url' => $store->callback_url,
                'active'       => true,
            ],
        ]);
    }

    /**
     * Remove the carrier service from the store.
     */
    public function deleteCarrier(Store $store): array
    {
        if (empty($store->shopify_id)) {
            return [];
        }

        return $this->request($store, 'DELETE', "/carrier_services/{$store->shopify_id}.json");
    }

    /**
     * Create the carrier service, or update it if one is already registered.
     * Call this whenever a store's settings are saved.
     */
    public function syncCarrier(Store $store): int
    {
        if (!empty($store->shopify_id)) {
            try {
                $this->updateCarrier($store);

                return (int) $store->shopify_id;
            } catch (Exception $e) {
                // The stored id may be stale (deleted in Shopify) - fall through
                // and register a fresh one. Do NOT swallow auth failures this
                // way, or a bad credential silently creates a duplicate.
                if (str_contains($e->getMessage(), 'HTTP 401')
                    || str_contains($e->getMessage(), 'rejected the credentials')) {
                    throw $e;
                }

                Log::warning('Carrier update failed, recreating', [
                    'store' => $store->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->createCarrier($store);
    }
}