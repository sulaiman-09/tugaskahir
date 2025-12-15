<?php

namespace App\Services;

use App\Models\SudirmanTowerAddress;
use Illuminate\Support\Collection;

class SudirmanParkService
{
    /**
     * Daftar tower unik.
     */
    public function getTowers(): Collection
    {
        return SudirmanTowerAddress::query()
            ->select('tower')
            ->distinct()
            ->orderBy('tower')
            ->get();
    }

    /**
     * Daftar lantai unik untuk tower tertentu.
     */
    public function getFloorsByTower(string $tower): Collection
    {
        return SudirmanTowerAddress::query()
            ->where('tower', $tower)
            ->select('floor')
            ->distinct()
            ->orderBy('floor')
            ->get();
    }

    /**
     * Daftar unit untuk kombinasi tower + floor.
     */
    public function getUnits(string $tower, string $floor): Collection
    {
        return SudirmanTowerAddress::query()
            ->where('tower', $tower)
            ->where('floor', $floor)
            ->select('id', 'unit', 'is_active')
            ->orderBy('unit')
            ->get();
    }
}
