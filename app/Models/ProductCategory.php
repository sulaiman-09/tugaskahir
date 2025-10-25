<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'show_price',
    ];

    public $timestamps = true; // sesuaikan jika tabel punya created_at dan updated_at

    // Relasi ke produk
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}
