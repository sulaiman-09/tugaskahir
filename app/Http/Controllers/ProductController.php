<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Data dummy
        $product = [
            (object)[
                'category_name' => 'Broadband Internet',
                'slug' => 'broadband-internet',
                'short_description' => 'Internet cepat dan stabil untuk rumah tangga dan bisnis kecil',
                'show_price' => true,
                'benefits' => [
                    ['icon' => '📺', 'text' => 'Bonus puluhan channel TV'],
                    ['icon' => '📶', 'text' => 'Koneksi stabil untuk aktivitas online'],
                    ['icon' => '💰', 'text' => 'Harga terjangkau untuk rumah tangga'],
                    ['icon' => '∞', 'text' => 'Internet tanpa batasan kuota'],
                ]
            ],
            (object)[
                'category_name' => 'Business Solutions',
                'slug' => 'business-solutions',
                'short_description' => 'Solusi komprehensif untuk konektivitas dan entertainment bisnis',
                'show_price' => false,
                'benefits' => [
                    ['icon' => '⚡', 'text' => 'Prioritas layanan pelanggan'],
                    ['icon' => '📡', 'text' => 'Memenuhi kebutuhan bisnis modern'],
                    ['icon' => '🌐', 'text' => 'IP statis untuk server dan hosting'],
                    ['icon' => '🛡️', 'text' => 'SLA jaminan kecepatan & uptime'],
                ]
            ],
            (object)[
                'category_name' => 'Promo Spesial Jepara',
                'slug' => 'promo-spesial-jepara',
                'short_description' => 'Nikmati internet cepat dan stabil dengan harga spesial khusus Jepara',
                'show_price' => true,
                'benefits' => [
                    ['icon' => '🏷️', 'text' => 'Promo khusus hanya untuk area Jepara'],
                    ['icon' => '📶', 'text' => 'Koneksi stabil untuk aktivitas harian'],
                    ['icon' => '💰', 'text' => 'Harga bersahabat untuk keluarga'],
                ]
            ]
        ];

        return view('product.index', compact('product'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'speed' => 'nullable|string|max:255',
            'description' => 'required|string',
            'web_image' => 'nullable|image',
            'apps_image' => 'nullable|image',
            'category' => 'required|string',
            'price' => 'nullable|numeric',
            'sudirman_product' => 'nullable|boolean',
        ]);

        // Simpan data (contoh saja, belum ke DB karena kamu pakai dummy)
        // dd($validated); // untuk debug

        // Redirect ke index atau tampilkan sukses
        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }
}
