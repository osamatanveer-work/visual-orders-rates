<?php

namespace App\Models;

use App\Traits\FingerprintModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShippingQuoteHeader extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'store_id',
        'shipto_id',
        'shipToName',
        'shipfrom_id',
        'shipFromName',
        'orderWeightInGrams',
        'orderWeightWithPackagingInGrams',
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
        'request_id' => 'integer',
        'store_id' => 'integer',
        'shipto_id' => 'integer',
        'shipToName' => 'string',
        'shipfrom_id' => 'integer',
        'shipFromName' => 'string',
        'orderWeightInGrams' => 'float',
        'orderWeightWithPackagingInGrams' => 'float'
    ];

    /**
     * findOrCreateAddress
     * This is just an alias call to (FingerprintModel) findOrCreateByFingerprint
     *
     * @param Address $model
     *
     * @return FingerprintModel
     */
    public static function findOrCreateAddress(self $model): FingerprintModel
    {
        return self::findOrCreateByFingerprint($model);
    }

    public function shipTo(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'shipto_id');
    }

    public function shipFrom(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'shipfrom_id');
    }

    public function store(): HasOne {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function services(): HasMany {
        return $this->hasMany(ShippingQuoteService::class, 'quote_id', 'id');
    }
}
