<?php

/*
|--------------------------------------------------------------------------
| Shopify
|--------------------------------------------------------------------------
|
| These values MUST live in a config file rather than being read with env()
| at runtime. Once `php artisan config:cache` has been run, Laravel no longer
| loads the .env file, and every runtime env() call returns null (or its
| hardcoded default). Reading them here means the cached config keeps the
| real values.
|
*/

return [

    /*
    | Admin API version used for every call. Bump this deliberately - Shopify
    | supports each version for 12 months from release.
    */
    'api_version' => env('SHOPIFY_API_VERSION', '2025-10'),

    /*
    | Guzzle timeouts, in seconds. Kept short: these calls run inside a request
    | cycle while an admin waits on a form submit.
    */
    'timeout'       => (int) env('SHOPIFY_HTTP_TIMEOUT', 15),
    'token_timeout' => (int) env('SHOPIFY_TOKEN_TIMEOUT', 10),

    /*
    | Seconds to refresh a client_credentials token before it actually expires.
    */
    'token_safety_margin' => (int) env('SHOPIFY_TOKEN_SAFETY_MARGIN', 120),

    /*
    | The name the carrier service is registered under in the merchant's
    | Shopify admin. Changing this renames it on the next sync.
    */
    'carrier_name' => env('SHOPIFY_CARRIER_NAME', 'Rate Shopper Carriers'),

];