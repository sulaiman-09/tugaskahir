<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductBannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $banner = $this->banner_products ?? null;
        $background = $this->background_image ?? null;

        $bannerUrl = $banner
            ? (Storage::disk('public')->exists($banner) ? Storage::url($banner) : $banner)
            : null;

        $backgroundUrl = $background
            ? (Storage::disk('public')->exists($background) ? Storage::url($background) : $background)
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'banner_products' => $bannerUrl,
            'background_image' => $backgroundUrl,
        ];
    }
}
