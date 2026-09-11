<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateQouteList extends Model
{
    use HasFactory;

    protected $table = 'rate_qoute_lists';

    protected $fillable = [
        'rate_qoute_id',
        'store_id',
        'carrier_id',
        'service_name',
        'markup_id',
        'qoute_amount',
        'retail_price',
        'profit_margin',
    ];

    public function markup()
    {
        return $this->belongsTo(Markup::class, 'markup_id', 'id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

}
