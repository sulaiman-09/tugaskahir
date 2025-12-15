<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityDistrictResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->external_id ?? null,
            'name' => $this->name,
            'province_id' => $this->province_id,
        ];
    }
}
