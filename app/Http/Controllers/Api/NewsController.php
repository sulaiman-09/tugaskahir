<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        $news = News::orderByDesc('news_created_date')->get();
        $data = NewsResource::collection($news)->resolve();

        return response()->json([
            'status' => 'success',
            'message' => 'News retrieved successfully',
            'data' => $data,
        ]);
    }
}
