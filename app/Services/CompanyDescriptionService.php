<?php

namespace App\Services;

use App\Models\CompanyDescription;

class CompanyDescriptionService
{
    /**
     * Ambil satu record company description untuk kebutuhan web.
     */
    public function getForWeb(): ?CompanyDescription
    {
        return CompanyDescription::query()
            ->orderByDesc('id')
            ->first();
    }
}
