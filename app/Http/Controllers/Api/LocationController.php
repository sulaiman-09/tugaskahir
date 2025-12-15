<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Http\Resources\CityDistrictResource;
use App\Http\Resources\SubdistrictResource;
use App\Http\Resources\VillageResource;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function provinces(LocationService $service): JsonResponse
    {
        $provinces = ProvinceResource::collection($service->getProvinces())->resolve();
        return response()->json([
            'status' => 'success',
            'message' => 'Provinces retrieved successfully',
            'data' => $provinces,
        ]);
    }

    public function cityDistricts(LocationService $service, $province): JsonResponse
    {
        $cities = CityDistrictResource::collection(
            $service->getCityDistrictsByProvince($province)
        )->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'City districts retrieved successfully',
            'data' => $cities,
        ]);
    }

    public function subdistricts(LocationService $service, $cityDistrict): JsonResponse
    {
        $subs = SubdistrictResource::collection(
            $service->getSubdistrictsByCityDistrict($cityDistrict)
        )->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Subdistricts retrieved successfully',
            'data' => $subs,
        ]);
    }

    public function villages(LocationService $service, $subdistrict): JsonResponse
    {
        $villages = VillageResource::collection(
            $service->getVillagesBySubdistrict($subdistrict)
        )->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Villages retrieved successfully',
            'data' => $villages,
        ]);
    }
}
