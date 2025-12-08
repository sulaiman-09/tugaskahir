<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CareerStoreRequest;
use App\Http\Requests\Api\CareerUpdateRequest;
use App\Models\Career;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = $request->get('q');
        $type = $request->get('type');

        $careers = Career::query()
            ->when($q, fn($query) => $query->where('title', 'like', "%{$q}%"))
            ->when($type, fn($query) => $query->where('type', $type))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->through(fn($career) => $this->transform($career));

        return response()->json([
            'status' => 'success',
            'message' => 'Careers retrieved successfully',
            'data' => $careers->items(),
            'meta' => [
                'current_page' => $careers->currentPage(),
                'per_page' => $careers->perPage(),
                'total' => $careers->total(),
                'last_page' => $careers->lastPage(),
            ],
        ]);
    }

    public function store(CareerStoreRequest $request): JsonResponse
    {
        $data = $this->preparePayload($request->validated());
        $career = Career::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Career created successfully',
            'data' => $this->transform($career),
        ], 201);
    }

    public function show(Career $career): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Career retrieved successfully',
            'data' => $this->transform($career),
        ]);
    }

    public function update(CareerUpdateRequest $request, Career $career): JsonResponse
    {
        $data = $this->preparePayload($request->validated());
        $career->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Career updated successfully',
            'data' => $this->transform($career),
        ]);
    }

    public function destroy(Career $career): JsonResponse
    {
        $career->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Career deleted successfully',
        ]);
    }

    protected function transform(Career $career): array
    {
        return [
            'id' => $career->id,
            'title' => $career->title,
            'slug' => $career->slug,
            'description' => $career->description,
            'image_path' => $career->image_path,
            'job_requirements' => $this->splitLines($career->job_requirements),
            'job_description' => $this->splitLines($career->job_description),
            'location' => $career->location,
            'type' => $career->type,
            'education_level' => $career->education_level,
            'is_active' => (bool) $career->is_active,
            'published_at' => $career->published_at?->toDateTimeString(),
            'created_at' => $career->created_at?->toDateTimeString(),
            'updated_at' => $career->updated_at?->toDateTimeString(),
        ];
    }

    protected function preparePayload(array $data): array
    {
        if (isset($data['job_requirements']) && is_array($data['job_requirements'])) {
            $data['job_requirements'] = implode(PHP_EOL, array_filter($data['job_requirements']));
        }

        if (isset($data['job_description']) && is_array($data['job_description'])) {
            $data['job_description'] = implode(PHP_EOL, array_filter($data['job_description']));
        }

        if (isset($data['is_active'])) {
            $data['is_active'] = $data['is_active'] ? 1 : 0;
        }

        return $data;
    }

    protected function splitLines(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_values(array_filter(preg_split('/\r\n|\r|\n/', $value), fn($line) => trim($line) !== ''));
    }
}
