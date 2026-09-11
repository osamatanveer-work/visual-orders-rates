<?php

namespace App\Libraries;

use PhpUnitConversion\Unit\Mass;

class UnitConversions
{
    public static function ounces_to_grams(float $p_ounces): float
    {
        $ounces = new Mass\Ounce($p_ounces);
        $grams = Mass\Gram::from($ounces);

        return floatval($grams->format(4, false));
    }

    public static function grams_to_ounces(float $p_grams): float {
        $grams = new Mass\Gram($p_grams);
        $ounces = Mass\Ounce::from($grams);

        return floatval($ounces->format(4,false));
    }

    public static function grams_to_pounds(float $p_grams): float {
        $grams = new Mass\Gram($p_grams);
        $pounds = Mass\Pound::from($grams);

        return floatval($pounds->format(4, false));
    }
}