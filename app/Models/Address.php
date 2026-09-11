<?php

namespace App\Models;

use App\Traits\FingerprintModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory, FingerprintModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address1',
        'address2',
        'address3',
        'city',
        'stateOrProvince',
        'postalCode',
        'countryCode',
        'fingerprint'
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
        'address1' => 'string',
        'address2' => 'string',
        'address3' => 'string',
        'city' => 'string',
        'stateOrProvince' => 'string',
        'postalCode' => 'string',
        'countryCode' => 'string',
        'fingerprint' => 'string'
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
}
