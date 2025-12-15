<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductRegisterResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * List produk (dengan kategori) untuk endpoint Web - Get Product.
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')
            ->orderBy('id', 'asc')
            ->get();

        $data = ProductRegisterResource::collection($products)->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $data,
        ]);
    }
}
