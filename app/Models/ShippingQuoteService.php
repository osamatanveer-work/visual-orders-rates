<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShippingQuoteService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quote_id',
        'service_id',
        'serviceName',
        'serviceCode',
        'serviceCost',
        'markupCost',
        'totalCostWithMarkup',
        'isFiltered',
        'filteredNote',
        'minDeliveryDate',
        'maxDeliveryDate',
        'transitBusinessDays',
        'deliveryEstimateSource'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'isFiltered' => true,
        'filteredNote' => 'Default Filter',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quote_id' => 'integer',
        'service_id' => 'integer',
        'serviceName' => 'string',
        'serviceCode' => 'string',
        'serviceCost' => 'float',
        'markupCost' => 'float',
        'totalCostWithMarkup' => 'float',
        'isFiltered' => 'boolean',
        'filteredNote' => 'string',
        'minDeliveryDate' => 'immutable_datetime',
        'maxDeliveryDate' => 'immutable_datetime',
        'transitBusinessDays' => 'integer',
        'deliveryEstimateSource' => 'string'
    ];

    /**
     * True when this service carries a real delivery estimate.
     *
     * Never assume one is present: USPS via ShipStation returns no transit
     * data whatsoever, and any carrier can omit it for a given lane.
     */
    public function hasDeliveryEstimate(): bool
    {
        return !is_null($this->minDeliveryDate) || !is_null($this->maxDeliveryDate);
    }

    public function quote(): BelongsTo {
        return $this->belongsTo(ShippingQuoteHeader::class,'quote_id','id');
    }

    public function service(): BelongsTo {
        return $this->belongsTo(CarrierService::class,'service_id','id');
    }

    /**
     * Scope a query to only include popular users.
     */
    public function scopeOfQuote(Builder $query, int $quote_id): void
    {
        $query->where('quote_id', '=', $quote_id)
            ->where('isFiltered', '=', false);
    }
}