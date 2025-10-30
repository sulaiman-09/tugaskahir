<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners'; // pastikan sesuai nama tabel di database kamu

    protected $fillable = [
        'name',
        'path',
        'path_apps',
        'is_active', // gunakan ini
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
