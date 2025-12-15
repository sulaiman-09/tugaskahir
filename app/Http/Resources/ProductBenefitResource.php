<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductBenefitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $icon = $this->icon ?? null;
        $iconUrl = null;
        if ($icon) {
            $iconUrl = Storage::disk('public')->exists($icon)
                ? Storage::url($icon)
                : $icon; // fallback ke nilai asli jika bukan di storage lokal
        }

        return [
            'id' => $this->id,
            'description' => $this->description,
            'icon_url' => $iconUrl,
        ];
    }
}
