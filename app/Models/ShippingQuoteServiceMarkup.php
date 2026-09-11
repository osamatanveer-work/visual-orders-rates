<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShippingQuoteServiceMarkup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id',
        'markup_id',
        'markupOrigin',
        'markupType',
        'amount',
        'note',
        'startingCost',
        'endingCost',
        'formula'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'service_id' => 'integer',
        'markup_id' => 'integer',
        'markupOrigin' => 'string',
        'markupType' => 'string',
        'amount' => 'float',
        'note' => 'string',
        'startingCost' => 'float',
        'endingCost' => 'float',
        'formula' => 'string'
    ];

    public function service(): HasOne
    {
        return $this->hasOne(ShippingQuoteService::class, 'id', 'service_id');
    }

    public function markupParent(): HasOne
    {
        $classStr = '';

        switch (strtoupper(trim($this->attributes['markupType']))) {
            case 'CARRIER':
                $classStr = Carrier::class;
                break;

            case 'SERVICE':
                $classStr = CarrierService::class;
                break;

            default:
                throw new Exception('markupType unknown for relationship');
        }

        return $this->hasOne($classStr, 'id', 'markup_id');
    }
}
