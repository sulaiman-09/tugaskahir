<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        // Dummy data contoh (bisa diganti dengan Model Banner)
        $banners = [
            [
                'name' => 'Izzi Spesial Area Baru',
                'web_image' => 'https://via.placeholder.com/250x80?text=Area+Baru',
                'mobile_image' => 'https://via.placeholder.com/100x100?text=Area+Baru',
                'status' => true,
            ],
            [
                'name' => 'Izzi Jepara',
                'web_image' => 'https://via.placeholder.com/250x80?text=Jepara',
                'mobile_image' => 'https://via.placeholder.com/100x100?text=Jepara',
                'status' => true,
            ],
            [
                'name' => 'Izzi Spesial Sukoharjo',
                'web_image' => 'https://via.placeholder.com/250x80?text=Sukoharjo',
                'mobile_image' => 'https://via.placeholder.com/100x100?text=Sukoharjo',
                'status' => true,
            ],
        ];

        return view('banner.index', compact('banners'));
    }
}
