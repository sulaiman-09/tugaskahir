<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        // Data dummy sementara
        $careers = [
            (object)[
                'id' => 249,
                'image' => 'https://via.placeholder.com/60x60.png?text=Career',
                'title' => 'Staf Technical Support E-Gov & Corporate',
                'type' => 'Full Time',
                'education' => 'SMA/SMK',
                'location' => 'Yogyakarta | Lumajang | Cirebon | Lembang',
                'status' => 'Active',
                'created_at' => '15 Jan 2025 23:03'
            ],
            (object)[
                'id' => 263,
                'image' => 'https://via.placeholder.com/60x60.png?text=Career',
                'title' => 'Staf Sales Retail',
                'type' => 'Full Time',
                'education' => 'SMA/SMK',
                'location' => 'Yogyakarta | Jepara',
                'status' => 'Active',
                'created_at' => '15 Jan 2025 23:55'
            ],
        ];

        return view('career.index', compact('careers'));
    }
}
