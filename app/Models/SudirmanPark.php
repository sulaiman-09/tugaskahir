<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SudirmanPark extends Model
{
    use HasFactory;

    protected $table = 'sudirman_customers'; // pastikan ini sesuai nama table

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'tower_address_id',
        'id_card_image',    // pastikan nama kolom sesuai database
        'package_id',
        'status',
        'status_change',
        'created_at',
        'updated_at',
    ];
}
