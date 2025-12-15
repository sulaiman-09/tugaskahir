<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductRegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $webImage = $this->web_image ?? $this->path ?? null;
        $appImage = $this->path_apps ?? null;

        $webUrl = $webImage
            ? (Storage::disk('public')->exists($webImage) ? Storage::url($webImage) : $webImage)
            : null;

        $appUrl = $appImage
            ? (Storage::disk('public')->exists($appImage) ? Storage::url($appImage) : $appImage)
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'path' => $webUrl,
            'path_apps' => $appUrl,
            'speed' => $this->speed,
            'description' => $this->description,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],
            'is_sudirman' => $this->is_sudirman ?? 0,
        ];
    }
}
