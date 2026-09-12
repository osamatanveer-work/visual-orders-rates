# Rate Shopper v2 — Redeploy Bundle (fixed)

This is your **most recent code** (`visual-orders-rates-3`) with the missing
pieces added so checkout shows all carriers again. Replace the code on the
serving instance with this.

## What was missing in the code you sent, and is now fixed here

The deployed code referenced things that were never actually included:

1. **`app/Support/TransitEstimate.php`** — a helper class used by the FedEx and
   UPS providers. It did not exist, so FedEx and UPS **crashed while reading
   rates** and dropped out, leaving only USPS — that's why checkout showed a
   single generic "Shipping" line. **Added.**
2. **The delivery-estimate database columns** — the `ShippingQuoteService` model
   writes `minDeliveryDate`, `maxDeliveryDate`, `transitBusinessDays`,
   `deliveryEstimateSource`, but no migration created them, so those saves
   failed and the Rate Quotes log stayed empty. **Migration added.**
3. **`Store.php` auth-mode constants** — `show.blade.php` uses
   `Store::AUTH_CLIENT_CREDENTIALS` / `AUTH_LEGACY_TOKEN`, but `Store.php` did
   not define them (would 500 the company page). **Added**, with the `auth_mode`
   column migration.
4. **`config/shipping.php`** — optional on/off switch for transit-time lookups.
   **Added.**

Nothing else was changed.

## ⚠️ Before deploying

**1. This bundle has NO `vendor/` folder** (dependencies). After extracting you
   must run `composer install` (see steps).

**2. This bundle has NO `.env`.** Keep the server's existing `.env` — back it up
   and restore it after replacing the code. Without it the app can't reach the
   database or the carriers.

**3. Deploy to the correct instance** — the one serving the 360 Products store's
   checkout (the **dev-rate3** instance). Not v1 production.

## Do database columns need to be added? — Yes, one command.

Replacing code does not change the database. Run **`php artisan migrate --force`**
after deploy. It only **adds** new empty columns (4 on `shipping_quote_services`,
1 on `stores`). No existing data is changed or deleted, and any column that
already exists is skipped automatically.

## Deploy steps

```bash
cd /path/to/the/instance          # folder that contains artisan

# 0. Safety backups
cp .env /root/rs.env.backup
tar czf /root/rs-oldcode.tgz .

# 1. Replace the code with this bundle (keep storage + .env)
#    Extract over the top, or clear the app code and unzip:
unzip visual-orders-rates-3-fixed.zip -d /tmp/rs-new
rsync -a --delete \
      --exclude='.env' --exclude='storage' \
      /tmp/rs-new/visual-orders-rates-3/ /path/to/the/instance/

# 2. Restore the live .env
cp /root/rs.env.backup /path/to/the/instance/.env

# 3. Install dependencies (no vendor/ is shipped)
composer install --no-dev --optimize-autoloader

# 4. Add the new database columns (safe / additive)
php artisan migrate --force

# 5. Clear caches and reload PHP
php artisan config:clear && php artisan cache:clear && php artisan optimize:clear
sudo systemctl reload php8.1-fpm      # or restart the app container
```

If it runs in Docker: extract, `docker compose build` / restart the app
container, then run steps 3–5 **inside** the container.

## Verify after deploy

```bash
ls -l app/Support/TransitEstimate.php
php artisan migrate:status | grep -E "auth_mode|delivery_estimate"
```

Then test checkout with a **fresh US address** (Shopify caches rates 15 min per
address). Expect multiple named carriers (USPS / UPS / FedEx) with delivery days,
and the Rate Quotes page populating again.

## Rollback

```bash
php artisan migrate:rollback --step=2
rm -rf /path/to/the/instance/* && tar xzf /root/rs-oldcode.tgz -C /path/to/the/instance/
cp /root/rs.env.backup /path/to/the/instance/.env
```
