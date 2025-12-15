<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SudirmanCustomerStoreRequest;
use App\Models\Product;
use App\Models\SudirmanPark;
use App\Models\SudirmanTowerAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SudirmanCustomerController extends Controller
{
    public function store(SudirmanCustomerStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Ambil tower/floor/unit dari alamat tower
        $address = SudirmanTowerAddress::find($data['tower_address_id']);

        // Ambil nama paket dari product
        $product = Product::find($data['package_id']);

        // Handle upload KTP menggunakan pola existing (folder ktp pada disk public)
        $ktpFilename = null;
        if ($request->hasFile('id_card_image')) {
            $file = $request->file('id_card_image');
            $ktpFilename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('ktp', $file, $ktpFilename);
        }

        // Siapkan payload sesuai kolom SudirmanPark yang sudah ada
        $payload = [
            'name' => $data['customer_name'],
            'phone' => $data['customer_phone'],
            'email' => $data['customer_email'] ?? null,
            'tower' => $address?->tower ?? null,
            'package' => $product?->name ?? (string) $data['package_id'],
            'status' => 'registration',
            'note' => $data['notes'] ?? null,
            'ktp' => $ktpFilename,
        ];

        $customer = SudirmanPark::create($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Sudirman customer created successfully',
            'data' => $customer,
        ], 201);
    }
}
