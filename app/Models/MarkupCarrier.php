<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MarkupCarrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'markup_id',
        'carrier_id',
        'markup_percent',
        'markup_fixed',
        'countries',
    ];

    public function carrier(): HasOne
    {
        return $this->hasOne(Carrier::class, 'id', 'carrier_id');
    }

    public function markup(): BelongsTo
    {
        return $this->belongsTo(Markup::class, 'id', 'markup_id');
    }

    public function countriesAsDecoded(): Attribute
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
