<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCarrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopify_id',
        'carrier_id',
        'store_id'
    ];

    public function carrier()
    {
        return $this->hasOne(Carrier::class);
    }

}
