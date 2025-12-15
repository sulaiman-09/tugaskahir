<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyDescriptionResource;
use App\Services\CompanyDescriptionService;
use Illuminate\Http\JsonResponse;

class CompanyDescriptionController extends Controller
{
    public function index(CompanyDescriptionService $service): JsonResponse
    {
        $desc = $service->getForWeb();

        if (!$desc) {
            return response()->json([
                'status' => 'success',
                'message' => 'No company description found',
                'data' => null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Company description retrieved successfully',
            'data' => (new CompanyDescriptionResource($desc))->resolve(),
        ]);
    }
}
