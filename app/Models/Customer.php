<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer_leads';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'email',
        'address',
        'referral_code',
        'province',
        'city',
        'district',
        'village',
        'product_category_id',
        'product_id',
        'latitude',
        'longitude',
        'coverage',
        'assign_to',
        'submitted',
        'submitted_at',
    ];

    // relasi ke tabel products
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // relasi ke tabel product_categories (division)
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
