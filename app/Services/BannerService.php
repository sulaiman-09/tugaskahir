<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Support\Collection;

class BannerService
{
    /**
     * Ambil semua banner dari DB lokal.
     */
    public function getAll(): Collection
    {
        return Banner::query()
            ->orderBy('id', 'asc')
            ->get();
    }
}
