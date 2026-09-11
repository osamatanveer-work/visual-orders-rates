# Rate Shopper — Fix: only one shipping option showing at checkout

**For the person deploying via GitHub. You do not need to understand the app —
just add three files, run one command, and test.**

---

## What was wrong

The last deploy added a new "delivery time per carrier" feature. The code for it
went live, but **two supporting pieces were never committed to the repo**:

1. A helper class the code calls — `app/Support/TransitEstimate.php`
2. The database columns that store the delivery estimates

Because the helper class is missing, **FedEx and UPS crash while calculating
rates** and get dropped, leaving only USPS — which is why checkout shows a single
generic **"Shipping"** option instead of the full USPS / UPS / FedEx list.
Because the columns are missing, quote records fail to save, which is why the
**Rate Quotes log stopped filling in**.

These three files add the missing pieces. Nothing that currently exists is
changed or removed — this is purely additive, so it is safe.

---

## The files (already laid out in the correct folders)

```
app/Support/TransitEstimate.php
database/migrations/2026_09_11_000000_add_delivery_estimate_to_shipping_quote_services.php
config/shipping.php
```

Copy each one to the **same path inside the project repo**, keeping the folder
structure. (`config/shipping.php` is optional but recommended — the other two are
required.)

---

## Deploy steps

From the project root on the server (or in your local clone, then push):

```bash
# 1. Put the three files in place (paths shown above), then commit
git add app/Support/TransitEstimate.php \
        database/migrations/2026_09_11_000000_add_delivery_estimate_to_shipping_quote_services.php \
        config/shipping.php
git commit -m "Add missing TransitEstimate helper + delivery-estimate columns (fix single-rate at checkout)"

# 2. Deploy as you normally do (pull on the server), then run the migration:
php artisan migrate --force

# 3. Clear caches so the new class and config are picked up:
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

---

## How to verify (do this before and after)

**Before deploying — check the two required files are valid PHP:**

```bash
php -l app/Support/TransitEstimate.php
php -l database/migrations/2026_09_11_000000_add_delivery_estimate_to_shipping_quote_services.php
```

Both should print `No syntax errors detected`.

**Preview the migration without touching data (optional):**

```bash
php artisan migrate --pretend
```

**After deploying — test a real checkout:**

1. Open the store, add a product to the cart, go to checkout.
2. Enter a **complete US address** (street, city, state, ZIP).
3. Wait ~5 seconds. You should now see **multiple named carrier options**
   (e.g. USPS Ground Advantage, UPS Ground, FedEx Home Delivery) instead of one
   generic "Shipping" line.
4. To test again, **change the city and ZIP** — Shopify caches rates for 15
   minutes per address, so reusing the same address just replays the old answer.

You can also confirm the **Rate Quotes** admin page starts listing quotes again.

---

## Rollback (if ever needed)

```bash
php artisan migrate:rollback --step=1   # removes the four added columns
git revert HEAD                         # removes the three files
```

The migration only drops the four new (empty) columns, so rolling back is safe.

---

## One thing worth flagging to MA

There is also a smaller, separate item: on the carrier screens, the **"Api Name"**
column is blank for every service. That column controls the clean carrier name
sent to Shopify. Rates will work without it, but filling it in gives tidier
labels at checkout. This fix does **not** touch that — it only restores the
missing carriers. Mention it if labels still look off after deploying.

---

## Why this happened (so it doesn't recur)

The live server had code that depended on files which were never committed to
git. Whenever code is edited directly on the server instead of through the repo,
the next clean deploy silently loses it. Keeping every change in git — and running
`php artisan migrate` on deploy — prevents this class of "half-shipped feature"
problem.
