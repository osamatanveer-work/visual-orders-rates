<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestHeader extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endpointName',
        'isTest',
        'isSuccess',
        'input',
        'output',
        'additionalData'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'isTest' => true,
        'isSuccess' => false
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'endpointName' => 'string',
        'isTest' => 'boolean',
        'isSuccess' => 'boolean',
        'input' => 'array',
        'output' => 'array',
        'additionalData' => 'array'
    ];
}
