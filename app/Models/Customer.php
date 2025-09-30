<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','phone','email','address','referral_code',
        'province','city','district','village','division',
        'product_category','product','coverage','latitude',
        'longitude','assign_to','submitted_at','submitted'
    ];
}
