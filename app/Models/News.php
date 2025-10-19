<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'news_id';
    public $timestamps = false; // <— penting banget biar Laravel gak nyari created_at / updated_at
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'news_title',
        'news_content',
        'news_image',
        'news_image_app',
        'news_image_caption',
        'news_created_date',
        'news_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'news_user_id', 'id');
    }
}
