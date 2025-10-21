<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsContent extends Model
{
    use HasFactory;

    protected $table = 'content_sections';

    protected $fillable = [
        'content_type_id',
        'title',
        'name',
        'description',
        'order',
        'image',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
