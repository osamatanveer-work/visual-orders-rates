<?php

/**
 * Shipping behaviour toggles.
 *
 * request_transit_times controls whether the FedEx and UPS providers ask the
 * carrier for delivery-time estimates (FedEx returnTransitTimes / UPS
 * /Shoptimeintransit). The provider code already defaults this to true via
 * config('shipping.request_transit_times', true), so this file is optional —
 * it just makes the switch explicit and lets you turn estimates off from .env
 * without touching code (SHIPPING_REQUEST_TRANSIT_TIMES=false) if a carrier
 * endpoint ever misbehaves.
 */
return [
    'request_transit_times' => env('SHIPPING_REQUEST_TRANSIT_TIMES', true),
];
