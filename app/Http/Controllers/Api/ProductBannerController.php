<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductBannerResource;
use App\Services\ProductBannerService;
use Illuminate\Http\JsonResponse;

class ProductBannerController extends Controller
{
    public function index(ProductBannerService $service): JsonResponse
    {
        $categories = $service->getForWeb();
        $data = ProductBannerResource::collection($categories)->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Product banners retrieved successfully',
            'data' => $data,
        ]);
    }
}
