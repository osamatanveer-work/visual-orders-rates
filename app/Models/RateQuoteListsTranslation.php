<?php

namespace App\Models;

use App\Traits\FingerprintModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RateQuoteListsTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quote_list_id',
        'service_id'
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
        'quote_list_id' => 'integer',
        'service_id' => 'integer'
    ];

    public function quoteList(): HasOne {
        return $this->hasOne(RateQouteList::class,'id','quote_list_id');
    }

    public function quoteServices(): HasOne {
        return $this->hasOne(ShippingQuoteService::class,'id','service_id');
    }
}
