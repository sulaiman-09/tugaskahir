<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $path = $this->path ?? null;
        $pathApps = $this->path_apps ?? null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $path ? (Storage::disk('public')->exists($path) ? Storage::url($path) : $path) : null,
            'path_apps' => $pathApps ? (Storage::disk('public')->exists($pathApps) ? Storage::url($pathApps) : $pathApps) : null,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
