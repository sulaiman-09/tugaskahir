<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'careers'; // sesuaikan dengan nama table
    protected $fillable = [
        'title',
        'type',
        'education_level',
        'location',
        'job_overview',
        'job_requirements',
        'description',
        'image',
        'status',
    ];
}
