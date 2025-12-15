<?php

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Support\Collection;

class ProductBannerService
{
    /**
     * Ambil kategori produk yang memiliki banner untuk kebutuhan web.
     */
    public function getForWeb(): Collection
    {
        return ProductCategory::query()
            ->whereNotNull('banner_products')
            ->orderBy('id', 'asc')
            ->get();
    }
}
