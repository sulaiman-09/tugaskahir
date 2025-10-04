<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SudirmanPark extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'tower',
        'package',
        'status',
        'ktp',
        'note',
    ];
}
