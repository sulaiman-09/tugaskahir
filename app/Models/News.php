<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news'; // pastikan sesuai nama tabel

    protected $fillable = [
        'news_title',
        'news_content',
        'news_image',
        'created_at',
        'updated_at',
    ];
}
