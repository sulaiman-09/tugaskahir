<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductBenefitResource;
use App\Services\ProductBenefitService;
use Illuminate\Http\JsonResponse;

class ProductBenefitController extends Controller
{
    public function index(ProductBenefitService $service): JsonResponse
    {
        $benefits = $service->getAll();
        $data = ProductBenefitResource::collection($benefits)->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Product benefits retrieved successfully',
            'data' => $data,
        ]);
    }
}
