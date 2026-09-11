<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarrierService extends Model
{
    use HasFactory;

    /*
    public function carrier(): HasOne
    {
        return $this->hasOne(Carrier::class, 'name', 'carrier_name');
    }
    */

    public function carrier(): BelongsTo {
        return $this->belongsTo(Carrier::class,'carrier_name','name');
    }

    public function apiAllowedAsBool(): Attribute
    {
        return new Attribute(
            get: function (mixed $value, array $attributes) {
                if ($attributes['api_allowed'] === '1') {
                    return true;
                }

                return false;
            }
        );
    }
}
