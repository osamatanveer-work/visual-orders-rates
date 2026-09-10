# Rate Shopper v2 — Upload and Go

This package is configured and ready. **You do not need to edit any file.**

Your existing `.env` is included exactly as it was — database, UPS, FedEx,
ShipStation and mail settings are all unchanged. No keys were rotated or
altered.

## Deploy

```bash
# upload + extract, then:
docker compose up -d --build
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app php artisan migrate
```

`php artisan migrate` adds the two new columns (`shopify_client_id`,
`shopify_client_secret`). Nothing existing is dropped or renamed.

## DNS and SSL

In GoDaddy, add an **A record** for host `rates2` on `visualorders.com`, pointing
at the new server's IP. Once it resolves:

```bash
certbot --nginx -d rates2.visualorders.com
```

Shopify requires https for carrier callbacks — it will not call an http URL.

## Why there is nothing to configure

The old code built carrier callback URLs from `APP_URL`, which is why a second
instance would have needed editing. This version derives the callback from the
domain you are actually browsing when you add a store:

```php
$base = request()->getSchemeAndHttpHost();   // whatever host you're on
return $base . '/api/carrier-services/' . $store->slug;
```

So deploy it to any domain and it registers the correct callback automatically.
`APP_URL` is already set to `https://rates2.visualorders.com/`, so email links
are correct too. The self-configuring callback is simply a safety net.

## Connecting a store

1. Shopify Dev Dashboard → create an app with scopes `read_shipping,
   write_shipping` → install it on the store (app and store must be in the same
   Shopify organization).
2. Dev Dashboard → app → Settings → copy **Client ID** and **Client Secret**.
3. Rate Shopper → Companies → the company → **Add New Store**:
   - **Store Url** — the store's permanent `xxx.myshopify.com` address
     (Shopify → Settings → Domains, the "Connected" one). The client keeps using
     their custom domain for everything customer-facing; this is only used for
     API calls.
   - **Shopify Client ID** and **Shopify Client Secret**
4. Submit. The token is fetched and the carrier service is registered in one go.
5. Shopify → Settings → Shipping and delivery → profile → Add rate → "Use
   carrier or app to calculate rates" → **Rate Shopper Carriers**.

The store's plan must support carrier-calculated shipping (Advanced, Plus, Grow
with annual billing or the add-on, or a development store).

## One housekeeping item

On the OLD server, `storage/logs/laravel.log` had grown to **5.2 GB**, which is
the most likely cause of its 502s. Clear it:

```bash
truncate -s 0 storage/logs/laravel.log
df -h
```

This package ships with the same logging settings you had, so consider adding
logrotate on the new box (see DEPLOYMENT.md).

Full server setup, nginx config and troubleshooting: **DEPLOYMENT.md**.
