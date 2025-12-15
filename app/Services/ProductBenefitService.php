<?php

namespace App\Services;

use App\Models\ProductBenefit;
use Illuminate\Support\Collection;

class ProductBenefitService
{
    /**
     * Ambil semua benefit produk dari DB lokal.
     */
    public function getAll(): Collection
    {
        return ProductBenefit::query()
            ->orderBy('id', 'asc')
            ->get();
    }
}
