<?php

namespace App\Services;

use App\Models\AboutUs;

class AboutUsService
{
    /**
     * Ambil satu record About Us untuk kebutuhan web.
     */
    public function getForWeb(): ?AboutUs
    {
        return AboutUs::query()
            ->orderByDesc('id')
            ->first();
    }
}
