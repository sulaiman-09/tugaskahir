<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerLeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_name'        => ['required', 'string'],
            'customer_phone'       => ['required', 'string'],
            'customer_address'     => ['required', 'string'],
            'latitude'             => ['nullable'],
            'longitude'            => ['nullable'],
            'email'                => ['nullable', 'email'],
            'referral_code'        => ['nullable', 'string'],
            'region_id'            => ['required', 'string'],
            'product_id'           => ['required', 'integer', 'exists:products,id'],
            'product_category_id'  => ['required', 'integer', 'exists:product_categories,id'],
        ]);

        $lead = Customer::create([
            'customer_name'       => $data['customer_name'],
            'customer_phone'      => $data['customer_phone'],
            'email'               => $data['email'] ?? null,
            'customer_address'    => $data['customer_address'],
            'address'             => $data['customer_address'],
            'referral_code'       => $data['referral_code'] ?? null,
            'region_id'           => $data['region_id'],
            'product_id'          => $data['product_id'],
            'product_category_id' => $data['product_category_id'],
            'latitude'            => $data['latitude'] ?? null,
            'longitude'           => $data['longitude'] ?? null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer lead created successfully',
            'data'    => $lead,
        ], 201);
    }
}
