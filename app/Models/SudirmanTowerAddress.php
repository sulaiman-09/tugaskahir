<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SudirmanTowerAddress extends Model
{
    use HasFactory;

    protected $table = 'sudirman_tower_addresses';
    protected $fillable = [
        'tower',
        'floor',
        'unit',
        'is_active',
    ];
}
