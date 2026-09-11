<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'shop_url',
        'img',
        'markup_id'
    ];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function markup(): HasOne
    {
        return $this->hasOne(Markup::class, 'id', 'markup_id');
    }

}
