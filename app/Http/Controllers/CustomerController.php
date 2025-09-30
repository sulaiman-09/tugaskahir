<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Data dummy lengkap sesuai 12 kolom
        $customers = [
            [
                'id' => 1,
                'name' => 'Nasya',
                'phone' => '08123456789',
                'email' => 'nasya@example.com',
                'address' => 'Jl. Sudirman No. 1',
                'latitude' => '-7.797068',
                'longitude' => '110.370529',
                'coverage' => 'Yogyakarta Internet 50Mbps',
                'product' => 'Paket Premium',
                'assign_to' => 'Admin',
                'submitted_at' => '2025-09-25',
                'submitted' => 'Yes',
            ],
            [
                'id' => 2,
                'name' => 'Budi',
                'phone' => '08129876543',
                'email' => 'budi@example.com',
                'address' => 'Jl. Malioboro No. 2',
                'latitude' => '-7.792345',
                'longitude' => '110.367890',
                'coverage' => 'Yogyakarta Internet 20Mbps',
                'product' => 'Paket Basic',
                'assign_to' => 'CS',
                'submitted_at' => '2025-09-24',
                'submitted' => 'No',
            ],
        ];

        return view('customer.index', compact('customers'));
    }
}