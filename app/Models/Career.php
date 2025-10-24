<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

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
    ];


    protected $casts = [
        'is_active' => 'boolean',
        'job_requirements' => 'array', // <-- tambahkan ini
    ];
}
