<?php

namespace App\Services;

use App\Models\Province;
use App\Models\CityDistrict;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Support\Collection;

class LocationService
{
    public function getProvinces(): Collection
    {
        return Province::query()
            ->orderBy('name')
            ->get();
    }

    public function getCityDistrictsByProvince($provinceIdOrCode): Collection
    {
        return CityDistrict::query()
            ->when($provinceIdOrCode, function ($q) use ($provinceIdOrCode) {
                $q->where('province_id', $provinceIdOrCode)
                    ->orWhere('external_id', $provinceIdOrCode);
            })
            ->orderBy('name')
            ->get();
    }

    public function getSubdistrictsByCityDistrict($cityDistrictIdOrCode): Collection
    {
        return Subdistrict::query()
            ->when($cityDistrictIdOrCode, function ($q) use ($cityDistrictIdOrCode) {
                $q->where('city_id', $cityDistrictIdOrCode)
                    ->orWhere('external_id', $cityDistrictIdOrCode);
            })
            ->orderBy('name')
            ->get();
    }

    public function getVillagesBySubdistrict($subdistrictIdOrCode): Collection
    {
        return Village::query()
            ->when($subdistrictIdOrCode, function ($q) use ($subdistrictIdOrCode) {
                $q->where('subdistrict_id', $subdistrictIdOrCode)
                    ->orWhere('external_id', $subdistrictIdOrCode);
            })
            ->orderBy('name')
            ->get();
    }
}
