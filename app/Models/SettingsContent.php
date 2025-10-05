<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'name',
        'type',
        'order',
        'status'
    ];
}
