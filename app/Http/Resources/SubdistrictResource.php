<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubdistrictResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->external_id ?? null,
            'name' => $this->name,
            'city_id' => $this->city_id,
        ];
    }
}
