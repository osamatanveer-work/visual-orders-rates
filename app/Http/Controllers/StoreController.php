<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Store;
use App\Services\ShopifyApiService;
use App\Services\ShopifyTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * StoreController
 *
 * Handles both Shopify auth styles:
 *   - legacy custom app permanent access token (still valid; cannot be reissued)
 *   - Dev Dashboard Client ID + Secret via client_credentials
 *
 * Behaviour worth knowing about:
 *
 *  1. THE SLUG IS FROZEN ONCE THE CARRIER IS REGISTERED. The slug is the route
 *     key in /api/carrier-services/{slug}, which is baked into the callback URL
 *     Shopify has on file. Re-slugging a live store silently 404s every rate
 *     request and shipping options vanish at checkout with no error anywhere.
 *     Renaming still updates the display name; only the routing key is pinned.
 *
 *  2. callback_url IS ONLY PERSISTED AFTER SHOPIFY ACCEPTS IT. The previous
 *     version wrote it locally regardless, so a failed or skipped sync left the
 *     database claiming a callback Shopify had never been told about. On
 *     failure we keep the last known good value.
 *
 *  3. Shopify calls sit outside the update transaction. They stay inside the
 *     create transaction, where atomicity matters more than lock duration -
 *     a store row with no carrier service is worse than a brief lock.
 */
class StoreController extends Controller
{
    protected ShopifyApiService $shopifyServices;
    protected ShopifyTokenService $shopifyTokens;

    public function __construct(ShopifyApiService $shopifyServices, ShopifyTokenService $shopifyTokens)
    {
        $this->shopifyServices = $shopifyServices;
        $this->shopifyTokens = $shopifyTokens;
    }

    public function index($slug)
    {
        $company = Company::where('slug', $slug)->firstOrFail();
        $stores = Store::where('company_id', $company->id)->get();

        return view('stores.index', compact('company', 'stores'));
    }

    public function create($id)
    {
        $company = Company::find($id);

        return view('stores.create', compact('company'));
    }

    /**
     * Create a store and register its carrier service with Shopify.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'url'  => 'required',
        ]);

        // Either credential style is acceptable. A legacy token cannot be newly
        // minted from Shopify any more, but a store being migrated onto this
        // instance will already have one, and it still works.
        if (!$this->hasSubmittedCredentials($request)) {
            return back()->with(
                'error',
                'Enter either a Shopify API access token (legacy app), or a Client ID and Secret from the Dev Dashboard.'
            );
        }

        DB::beginTransaction();

        try {
            $company = Company::findOrFail($id);

            $store = Store::create([
                'company_id'            => $company->id,
                'name'                  => $request->name,
                'slug'                  => $this->uniqueSlug($request->name),
                'store_url'             => $this->normaliseUrl($request->url),
                'access_token'          => $request->filled('shopify_access_token') ? trim($request->shopify_access_token) : null,
                'shopify_client_id'     => $request->filled('client_id') ? trim($request->client_id) : null,
                'shopify_client_secret' => $request->filled('client_secret') ? trim($request->client_secret) : null,
            ]);

            // Held in memory only until Shopify confirms it.
            $store->callback_url = $this->callbackUrl($store);
            $store->shopify_id = $this->shopifyServices->syncCarrier($store);
            $store->save();

            DB::commit();

            return back()->with('success', 'Store created and registered with Shopify.');
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Store create failed', ['company' => $id, 'error' => $th->getMessage()]);

            return back()->with('error', 'Could not create store: ' . $th->getMessage());
        }
    }

    /**
     * Update a store, persist credentials, and re-sync the carrier service.
     *
     * Validation is deliberately lenient: several modals in the UI post to this
     * same route with partial payloads, so anything absent falls back to the
     * value already stored rather than being wiped. Blank secret fields mean
     * "keep the current one".
     */
    public function update(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id);
        } catch (Throwable $th) {
            return back()->with('error', 'Store not found.');
        }

        try {
            $payload = [];

            if ($request->filled('name')) {
                $payload['name'] = $request->name;

                // Only re-key the route while the store is not yet live in
                // Shopify. See the class docblock.
                if (empty($store->shopify_id)) {
                    $payload['slug'] = $this->uniqueSlug($request->name, $store->id);
                }
            }

            if ($request->filled('url')) {
                $payload['store_url'] = $this->normaliseUrl($request->url);
            }

            if ($request->filled('client_id')) {
                $payload['shopify_client_id'] = trim($request->client_id);
            }

            if ($request->filled('client_secret')) {
                $payload['shopify_client_secret'] = trim($request->client_secret);
            }

            // NOTE the field name. The "Add Carrier" modal on companies/show
            // posts its own `access_token` to this same route, so reading that
            // key here would let a carrier token overwrite the store's Shopify
            // credential. The Shopify field is posted as shopify_access_token.
            if ($request->filled('shopify_access_token')) {
                $payload['access_token'] = trim($request->shopify_access_token);
            }

            if (!empty($payload)) {
                DB::transaction(function () use ($store, $payload) {
                    $store->update($payload);
                });

                $store->refresh();
            }

            // Credentials may have changed - discard any cached token.
            $this->shopifyTokens->forget($store);

            $renameNote = ($request->filled('name') && !empty($store->shopify_id)
                && Str::slug($request->name) !== $store->slug)
                ? ' The internal address was left unchanged so the live Shopify callback keeps working.'
                : '';

            if (!$this->shopifyTokens->hasCredentials($store)) {
                // Nothing to talk to Shopify with. Only record the callback if
                // we have never registered one, so we never claim Shopify holds
                // a value it does not.
                if (empty($store->shopify_id)) {
                    $store->update(['callback_url' => $this->callbackUrl($store)]);
                }

                return back()->with(
                    'success',
                    'Store updated. Add a legacy access token, or a Client ID and Secret, to register it with Shopify.' . $renameNote
                );
            }

            $previousCallback = $store->callback_url;
            $store->callback_url = $this->callbackUrl($store);

            try {
                $store->shopify_id = $this->shopifyServices->syncCarrier($store);
                $store->save();
            } catch (Throwable $th) {
                // Shopify still holds the old callback, so keep matching it.
                $store->callback_url = $previousCallback;

                Log::error('Carrier sync failed on update', [
                    'store' => $store->id,
                    'mode'  => $store->auth_mode,
                    'error' => $th->getMessage(),
                ]);

                return back()->with(
                    'error',
                    'Store details were saved, but Shopify rejected the carrier update, so its settings are unchanged there: ' . $th->getMessage()
                );
            }

            $mode = $this->shopifyTokens->usesLegacyToken($store)
                ? ' (still using a legacy access token)'
                : '';

            return back()->with('success', 'Store updated and carrier service re-registered' . $mode . '.' . $renameNote);
        } catch (Throwable $th) {
            Log::error('Store update failed', ['store' => $id, 'error' => $th->getMessage()]);

            return back()->with('error', 'Could not update store: ' . $th->getMessage());
        }
    }

    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);

            try {
                $this->shopifyServices->deleteCarrier($store);
            } catch (Throwable $e) {
                // Removing it in Shopify is best effort - still delete locally.
                // NOTE: this leaves an orphaned carrier service in the
                // merchant's admin pointing at a dead endpoint. Remove it by
                // hand under Settings > Shipping and delivery if this fires.
                Log::warning('Carrier delete failed - orphaned service left in Shopify', [
                    'store'      => $store->id,
                    'shopify_id' => $store->shopify_id,
                    'error'      => $e->getMessage(),
                ]);
            }

            $this->shopifyTokens->forget($store);
            $store->delete();

            return back()->with('success', 'Store has been deleted successfully.');
        } catch (Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * True if this request carries at least one usable credential style.
     */
    private function hasSubmittedCredentials(Request $request): bool
    {
        return $request->filled('shopify_access_token')
            || ($request->filled('client_id') && $request->filled('client_secret'));
    }

    /**
     * stores.slug is UNIQUE, so a second store named the same thing would throw
     * a raw SQL error out of the create path. Suffix instead.
     */
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'store';
        $slug = $base;
        $n = 2;

        while (Store::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    /**
     * Shopify's Admin API only answers on the permanent .myshopify.com host,
     * so normalise whatever was typed down to that.
     */
    private function normaliseUrl(string $url): string
    {
        return 'https://' . ShopifyTokenService::shopDomain($url);
    }

    /**
     * Build the carrier callback URL.
     *
     * APP_URL is authoritative. The previous version derived this from
     * request()->getSchemeAndHttpHost(), which is the client-supplied Host
     * header: spoofable, inconsistent behind a proxy, and unavailable from
     * queue workers or tinker - so the same store could end up with different
     * callbacks depending on where the save came from. The request host is
     * kept only as a fallback for when APP_URL has not been configured.
     *
     * Shopify requires https for carrier callbacks, so the scheme is forced.
     */
    private function callbackUrl(Store $store): string
    {
        $base = trim((string) config('app.url'));

        if ($base === '' || $base === 'http://localhost') {
            $base = request() ? request()->getSchemeAndHttpHost() : '';
        }

        $base = preg_replace('#^http://#i', 'https://', rtrim($base, '/'));

        if ($base === '') {
            throw new \RuntimeException('APP_URL is not set, so the carrier callback URL cannot be built.');
        }

        return $base . '/api/carrier-services/' . $store->slug;
    }
}