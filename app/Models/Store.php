<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'store_url',
        'img',
        'callback_url',
        'access_token',
        'shopify_id',
        'shopify_client_id',
        'shopify_client_secret'
    ];

    public function shopify_ids()
    {
        return $this->hasMany(StoreCarrier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
