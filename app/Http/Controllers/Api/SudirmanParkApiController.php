<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SudirmanParkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SudirmanParkApiController extends Controller
{
    public function towers(SudirmanParkService $service): JsonResponse
    {
        $towers = $service->getTowers();

        return response()->json([
            'status' => 'success',
            'message' => 'Towers retrieved successfully',
            'data' => $towers,
        ]);
    }

    public function floors(Request $request, SudirmanParkService $service): JsonResponse
    {
        $request->validate([
            'tower' => ['required', 'string'],
        ]);

        $floors = $service->getFloorsByTower($request->query('tower'));

        return response()->json([
            'status' => 'success',
            'message' => 'Floors retrieved successfully',
            'data' => $floors,
        ]);
    }

    public function units(Request $request, SudirmanParkService $service): JsonResponse
    {
        $request->validate([
            'tower' => ['required', 'string'],
            'floor' => ['required', 'string'],
        ]);

        $units = $service->getUnits(
            $request->query('tower'),
            $request->query('floor')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Units retrieved successfully',
            'data' => $units,
        ]);
    }
}
