<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDescription extends Model
{
    use HasFactory;

    protected $table = 'company_desc';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
