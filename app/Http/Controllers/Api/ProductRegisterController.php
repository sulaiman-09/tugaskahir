<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductRegisterResource;
use App\Services\ProductRegisterService;
use Illuminate\Http\JsonResponse;

class ProductRegisterController extends Controller
{
    public function index(ProductRegisterService $service): JsonResponse
    {
        $products = $service->getRegisterProducts();
        $data = ProductRegisterResource::collection($products)->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $data,
        ]);
    }
}
