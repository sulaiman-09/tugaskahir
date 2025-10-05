<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_title',
        'news_content',
        'news_image',
        'news_image_app',
        'news_image_caption',
        'news_created_date',
        'admin',
    ];
}
