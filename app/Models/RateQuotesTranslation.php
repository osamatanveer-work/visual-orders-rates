<?php

namespace App\Models;

use App\Traits\FingerprintModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RateQuotesTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rate_quote_id',
        'quote_id'
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
        'rate_quote_id' => 'integer',
        'quote_id' => 'integer'
    ];

    public function rateQuote(): HasOne {
        return $this->hasOne(RateQoute::class,'id','rate_quote_id');
    }

    public function shippingQuote(): HasOne {
        return $this->hasOne(ShippingQuoteHeader::class,'id','quote_id');
    }
}
