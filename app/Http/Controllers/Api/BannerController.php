<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Services\BannerService;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(BannerService $service): JsonResponse
    {
        $banners = $service->getAll();
        $data = BannerResource::collection($banners)->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Banners retrieved successfully',
            'data' => $data,
        ]);
    }
}
