<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Markup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function markupcarriers(): HasMany
    {
        return $this->hasMany(MarkupCarrier::class, 'markup_id', 'id')->orderBy('id', 'asc');
    }

    public function markupservices(): HasMany
    {
        return $this->hasMany(MarkupService::class, 'markup_id', 'id')->orderBy('id', 'asc');
    }
}
