<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyDescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $image = $this->image_path ?? null;
        $imageUrl = $image
            ? (Storage::disk('public')->exists($image) ? Storage::url($image) : $image)
            : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'payload' => $this->payload ?? null,
            'image' => $imageUrl,
        ];
    }
}
