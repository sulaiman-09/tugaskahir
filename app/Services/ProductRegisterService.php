<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRegisterService
{
    /**
     * Ambil produk untuk kebutuhan registrasi.
     * Saat ini logika sederhana: semua produk yang aktif (show_price optional).
     */
    public function getRegisterProducts(): Collection
    {
        return Product::with('category')
            ->orderBy('id', 'asc')
            ->get();
    }
}
