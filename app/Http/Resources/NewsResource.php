<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class NewsResource extends JsonResource
{
    public function toArray($request): array
    {
        $web = $this->news_image
            ? (Storage::disk('public')->exists($this->news_image) ? Storage::url($this->news_image) : $this->news_image)
            : null;

        $app = $this->news_image_app
            ? (Storage::disk('public')->exists($this->news_image_app) ? Storage::url($this->news_image_app) : $this->news_image_app)
            : null;

        return [
            'id' => $this->news_id ?? $this->id,
            'title' => $this->news_title ?? null,
            'slug' => $this->slug ?? null,
            'content' => $this->news_content ?? null,
            'image' => $web,
            'image_app' => $app,
            'caption' => $this->news_image_caption ?? null,
        ];
    }
}
