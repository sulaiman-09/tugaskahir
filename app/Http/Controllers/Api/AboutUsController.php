<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutUsResource;
use App\Services\AboutUsService;
use Illuminate\Http\JsonResponse;

class AboutUsController extends Controller
{
    public function index(AboutUsService $service): JsonResponse
    {
        $about = $service->getForWeb();

        if (!$about) {
            return response()->json([
                'status' => 'success',
                'message' => 'No About Us data found',
                'data' => null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'About Us retrieved successfully',
            'data' => (new AboutUsResource($about))->resolve(),
        ]);
    }
}
