<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories'; // pastikan sesuai nama tabel kamu

    protected $fillable = [
        'category_name',
        'slug',
        'short_description',
        'show_price',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'show_price' => 'boolean',
    ];
}
