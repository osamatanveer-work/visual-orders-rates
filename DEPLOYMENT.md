# Rate Shopper v2 — Deployment Guide

A standalone copy of the rate shopper, updated for Shopify's current
`client_credentials` authentication. Deploy this to a **new server and new
domain**. Your existing instance at `rates.visualorders.com` is untouched and
keeps serving the stores already connected to it.

---

## What changed from the original

| Area | Original | This version |
|---|---|---|
| Shopify auth | Permanent access token pasted into a field | Client ID + Secret, token fetched automatically and cached (~24h) |
| `StoreController::update()` | Silently discarded the token; never re-registered the carrier | Saves credentials and re-registers via `syncCarrier()` |
| Carrier registration | Only on store **create** | On create **and** update |
| Store URL | Accepted custom domains (which the Admin API rejects) | Normalised to the permanent `.myshopify.com` host |
| API version | Hardcoded `2024-01` | `SHOPIFY_API_VERSION`, default `2025-10` |
| Store form | "Access Token" | "Shopify Client ID" + "Shopify Client Secret" |

New files: `app/Services/ShopifyTokenService.php`, plus a migration adding
`shopify_client_id` and `shopify_client_secret` to `stores`.

The rate engine (`app/ApiProviders/` — FedEx, UPS, ShipStation — and the markup
templates) is **completely unchanged**. Real carrier names and your markups work
exactly as before.

---

## 1. Server requirements

- PHP **8.1+** with `curl`, `mbstring`, `xml`, `bcmath`, `zip`, `mysql`
- MySQL 5.7+ / MariaDB
- Composer 2
- Nginx (or Apache) + a valid SSL certificate — Shopify **requires HTTPS** for
  carrier callbacks
- Node 18+ only if you intend to rebuild front-end assets (`public/build` is
  already included, so normally you do not)

## 2. Upload

Upload and extract this package to e.g. `/var/www/rateshopper`.

`vendor/` and `node_modules/` are intentionally excluded. Install dependencies:

```bash
cd /var/www/rateshopper
composer install --no-dev --optimize-autoloader
```

## 3. Configure

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

- **`APP_URL`** — the new domain, with `https://`. This is what carrier callback
  URLs are built from, so getting it wrong means Shopify calls the wrong host.
- Database credentials.
- Carrier credentials (UPS / FedEx / ShipStation) — copy from the old server.
  **Rotate them first**: the old values were committed in `.env.sample`.

## 4. Database

```bash
php artisan migrate
```

Moving existing data across? Dump from the old server and import, then run
`php artisan migrate` to add the two new columns.

## 5. Permissions

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 6. Nginx

```nginx
server {
    listen 443 ssl http2;
    server_name rates2.yourdomain.com;
    root /var/www/rateshopper/public;

    ssl_certificate     /etc/letsencrypt/live/rates2.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/rates2.yourdomain.com/privkey.pem;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 60;
    }

    location ~ /\.(?!well-known).* { deny all; }

    client_max_body_size 20M;
}

server {
    listen 80;
    server_name rates2.yourdomain.com;
    return 301 https://$host$request_uri;
}
```

Then:

```bash
nginx -t && systemctl reload nginx
php artisan config:cache && php artisan route:cache
```

## 7. DNS

In GoDaddy, add an **A record** for your chosen subdomain pointing at the new
server's IP. Wait for it to resolve, then issue the certificate:

```bash
certbot --nginx -d rates2.yourdomain.com
```

## 8. Log rotation — do not skip

The old server accumulated a **5.2 GB** `storage/logs/laravel.log`, which very
likely filled the disk and caused its 502s. `.env.example` already sets
`LOG_CHANNEL=daily` and `LOG_LEVEL=error`. Add a safety net:

```
/var/www/rateshopper/storage/logs/*.log {
    daily
    rotate 7
    compress
    missingok
    notifempty
    copytruncate
}
```

Save as `/etc/logrotate.d/rateshopper`.

---

## Connecting a Shopify store (the new flow)

1. **Shopify Dev Dashboard → Apps → Create app.** Give it the scopes
   `read_shipping, write_shipping`. Set the App URL to your new domain.
2. **Install the app on the store.** The `client_credentials` grant requires it,
   and app + store must be in the **same Shopify organization**.
3. **Dev Dashboard → your app → Settings** — copy the **Client ID** and **Secret**.
4. In Rate Shopper: **Companies → the company → Add New Store**, and enter:
   - **Store Url** — the permanent `xxx.myshopify.com` address, *not* the custom
     domain. Find it in Shopify under Settings → Domains.
   - **Shopify Client ID** and **Shopify Client Secret**
5. Submit. The app fetches a token and registers the carrier service in one step.
6. In Shopify: **Settings → Shipping and delivery → your profile → Add rate →
   "Use carrier or app to calculate rates"** — "Rate Shopper Carriers" will be there.

Editing a store re-registers the carrier automatically. Leave the secret field
blank when editing to keep the existing one.

---

## Requirements on the Shopify side

Carrier-calculated shipping is **not on every plan**. The store must be on
**Advanced** or **Plus**, or on **Grow** with annual billing or the
carrier-calculated-shipping add-on. Development stores have it enabled for
testing. On a paused ("Pause and Build") plan it will never appear.

---

## Troubleshooting

| Symptom | Cause |
|---|---|
| `shop_not_permitted` on token fetch | App and store are in different Shopify organizations, or the app is not installed on the store |
| Token request returns 401 | Wrong Client ID/Secret, or the secret was rotated in the Dev Dashboard |
| Carrier registers but never appears | Store plan lacks carrier-calculated shipping |
| Shopify never calls the callback | `APP_URL` wrong, or the domain has no valid SSL |
| 404 on the callback | `php artisan route:cache` after changing `APP_URL` |

Useful checks:

```bash
tail -f storage/logs/laravel.log
php artisan tinker
>>> app(\App\Services\ShopifyTokenService::class)->getToken(\App\Models\Store::find(1));
```

---

## A note on scale

The `client_credentials` grant only works for stores inside **your own** Shopify
organization. For external merchants you need full OAuth (managed install /
token exchange via the Shopify CLI). Plan for that before onboarding clients who
own their own Shopify accounts.
