<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Throwable;

/**
 * TransitEstimate
 * -----------------------------------------------------------------------------
 * Helper used by the carrier providers (Fedex, UPS) and by Shopify::generateReply()
 * to turn each carrier's delivery information into a concrete date for checkout.
 *
 * WHY THIS FILE EXISTS
 * The delivery-estimate feature was deployed referencing this class in three
 * files (app/ApiProviders/Fedex.php, app/ApiProviders/UPS.php and
 * app/RemoteStores/Shopify.php) but the class itself was never committed. With
 * the class missing, FedEx and UPS threw "Class App\Support\TransitEstimate not
 * found" while parsing their rate responses, their promises rejected, and only
 * USPS (which does not use this class) survived to checkout. That is why only a
 * single generic shipping option was showing.
 *
 * DESIGN RULE (matches the coder's own comments): this helper must NEVER throw.
 * A carrier that gives us unusable data should simply get no estimate — we must
 * never lose the rate itself. Every method therefore returns null / a safe
 * value on any problem instead of raising.
 */
class TransitEstimate
{
    /**
     * Date format Shopify expects in min_delivery_date / max_delivery_date.
     * ISO-8601 with timezone offset, e.g. 2026-09-15 17:00:00 +0000.
     * This is the same format the original hardcoded window used.
     */
    public const SHOPIFY_FORMAT = 'Y-m-d H:i:s O';

    /**
     * Parse an ISO-ish date/datetime string (FedEx commit dates, operational
     * detail dates, etc.) into an immutable date. Returns null on anything it
     * cannot understand.
     *
     * Accepts values such as:
     *   2026-09-15
     *   2026-09-15T18:00:00
     *   2026-09-15T18:00:00-05:00
     */
    public static function fromIso($value): ?CarbonImmutable
    {
        if (empty($value) || !is_string($value)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Parse a UPS EstimatedArrival date + time into an immutable date.
     *
     * UPS typically sends date as "Ymd" (e.g. 20260915) and time as "His"
     * (e.g. 170000), but both can arrive punctuated or absent. We strip to
     * digits and try the most likely layouts, falling back to a loose parse.
     * Returns null if there is no usable date.
     */
    public static function fromUps($date, $time = null): ?CarbonImmutable
    {
        if (empty($date)) {
            return null;
        }

        $digitsDate = preg_replace('/\D/', '', (string) $date);
        $digitsTime = preg_replace('/\D/', '', (string) ($time ?? ''));

        if (strlen($digitsDate) < 8) {
            // Not a full Ymd date — try a loose parse of whatever came in.
            try {
                return CarbonImmutable::parse((string) $date);
            } catch (Throwable $e) {
                return null;
            }
        }

        // Normalise the time portion to His.
        if (strlen($digitsTime) === 4) {
            $digitsTime .= '00';          // Hi  -> His
        } elseif (strlen($digitsTime) < 6) {
            $digitsTime = '000000';        // missing / partial -> midnight
        } else {
            $digitsTime = substr($digitsTime, 0, 6);
        }

        try {
            $parsed = CarbonImmutable::createFromFormat(
                'YmdHis',
                substr($digitsDate, 0, 8) . $digitsTime
            );

            return $parsed !== false ? $parsed : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Map a FedEx transit-time value to a whole number of business days.
     *
     * FedEx expresses this either as a word enum ("TWO_DAYS") or, on some
     * accounts, as a description that contains a digit ("2", "2 business days").
     * Returns null when nothing usable is present.
     */
    public static function fedexTransitDays($value): ?int
    {
        if (empty($value) || !is_string($value)) {
            return null;
        }

        $key = strtoupper(trim($value));

        $map = [
            'ONE_DAY'        => 1,
            'TWO_DAYS'       => 2,
            'THREE_DAYS'     => 3,
            'FOUR_DAYS'      => 4,
            'FIVE_DAYS'      => 5,
            'SIX_DAYS'       => 6,
            'SEVEN_DAYS'     => 7,
            'EIGHT_DAYS'     => 8,
            'NINE_DAYS'      => 9,
            'TEN_DAYS'       => 10,
            'ELEVEN_DAYS'    => 11,
            'TWELVE_DAYS'    => 12,
            'THIRTEEN_DAYS'  => 13,
            'FOURTEEN_DAYS'  => 14,
            'FIFTEEN_DAYS'   => 15,
            'SIXTEEN_DAYS'   => 16,
            'SEVENTEEN_DAYS' => 17,
            'EIGHTEEN_DAYS'  => 18,
            'NINETEEN_DAYS'  => 19,
            'TWENTY_DAYS'    => 20,
        ];

        if (array_key_exists($key, $map)) {
            return $map[$key];
        }

        // Fall back to a leading number embedded in the string, e.g. "2 days".
        if (preg_match('/\d+/', $key, $m)) {
            $days = (int) $m[0];

            return $days > 0 ? $days : null;
        }

        return null;
    }

    /**
     * Return "now plus N business days" (weekends skipped) as an immutable date.
     * Used when a carrier gives a transit-day count but no explicit date.
     * Non-positive counts collapse to today so we never return a past date.
     */
    public static function addBusinessDays($days): CarbonImmutable
    {
        $days = max(0, (int) $days);

        return CarbonImmutable::now()->addWeekdays($days);
    }
}
