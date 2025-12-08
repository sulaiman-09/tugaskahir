<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'education_level',
        'location',
        'description',
        'job_description',
        'job_requirements',
        'image_path',
        'is_active',
        'published_at',
    ];


    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];
}
