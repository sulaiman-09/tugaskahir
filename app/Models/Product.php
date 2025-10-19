<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tabel utama untuk produk
    protected $table = 'products'; 

    protected $fillable = [
        'product_category_id',
        'product_name',
        'slug',
        'short_description',
        'price',
        'show_price',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'show_price' => 'boolean',
    ];

    /**
     * Relasi ke kategori produk.
     * Setiap produk memiliki satu kategori.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
