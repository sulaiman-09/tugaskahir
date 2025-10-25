<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
    'product_category_id',
    'name',
    'speed',
    'description',
    'price',
    'show_price',
    'web_image',
    'apps_image'
];


    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
