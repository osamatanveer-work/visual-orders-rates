<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkupService extends Model
{
    use HasFactory;

    protected $fillable = [
        'markup_id',
        'carrier_id',
        'services',
        'amount',
        'markup_type',
        'countries'
    ];

    public function carrier()
    {
        return $this->hasOne(Carrier::class, 'id', 'carrier_id');
    }

    public function servicesDecoded(): Attribute
    {
        return new Attribute(
            get: function (mixed $value, array $attributes) {
                if ((is_null($attributes['services'])) or (strlen($attributes['services']) === 0)) {
                    return [];
                }

                $decoded = json_decode($attributes['services'], true);

                if (!is_array($decoded)) {
                    return [];
                }

                return $decoded;
            }
        );
    }

    public function countriesDecoded(): Attribute
    {
        return new Attribute(
            get: function (mixed $value, array $attributes) {
                if ((is_null($attributes['countries'])) or (strlen($attributes['countries']) === 0)) {
                    return [];
                }

                $decoded = json_decode($attributes['countries'], true);

                if (!is_array($decoded)) {
                    return [];
                }

                return $decoded;
            }
        );
    }
}
