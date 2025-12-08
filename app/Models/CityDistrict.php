<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityDistrict extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'external_id',
        'province_id',
        'name',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
