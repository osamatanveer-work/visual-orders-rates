<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'img',
        'description',
        'client_id',
        'client_secreat_key'
    ];
}
