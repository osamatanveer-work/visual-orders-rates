<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'length',
        'width',
        'height',
        'package_weight',
        'max_weight'
    ];

    /**
     * scopeOfStore
     * scope a query to only a box by store_id
     *
     * @param Builder $query
     * @param int $store_id
     *
     * @return void
     */
    public function scopeOfStore(Builder $query, int $store_id): void
    {
        $query->where('name', '=', 'Store: ' . strval($store_id));
    }

    /**
     * scopeOfDefaultName
     * scope a query to only a box by name = Default
     *
     * @param Builder $query
     * @param int $store_id
     *
     * @return void
     */
    public function scopeOfDefaultName(Builder $query): void
    {
        $query->where('name', '=', 'Default');
    }
}
