<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // INDEX: Menampilkan semua data customer
    public function index(Request $request)
    {
        $query = Customer::query();

        // Fitur filter tanggal
        if ($request->has('filter')) {
            $today = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            switch ($request->filter) {
                case 'today':
                    $query->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $yesterday);
                    break;
                case 'last_7_days':
                    $query->whereDate('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        $customers = $query->orderBy('created_at', 'desc')->get();
        return view('customer.index', compact('customers'));
    }

    // CREATE: Menampilkan form tambah customer
    public function create()
    {
        return view('customer.create');
    }

    // STORE: Menyimpan data baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'required|string',
            'referral_code' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'product_category' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'coverage' => 'nullable|string|max:255',
            'assign_to' => 'nullable|string|max:255',
            'submitted' => 'nullable|string|max:255',
            'submitted_at' => 'nullable|date',
        ]);

        Customer::create($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan dan tersimpan ke database.');
    }


    // EDIT: Menampilkan form edit data
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    // UPDATE: Menyimpan perubahan data ke database
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Data utama
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $id,
            'address' => 'required|string',
            'referral_code' => 'nullable|string|max:255',

            // Data wilayah
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',

            // Data tambahan
            'division' => 'nullable|string|max:255',
            'product_category' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
            'coverage' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',

            // Metadata
            'assign_to' => 'nullable|string|max:255',
            'submitted' => 'nullable|string|max:255',
            'submitted_at' => 'nullable|date',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Data customer berhasil diperbarui di tabel customers.');
    }

    // DESTROY: Menghapus data dari database
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil dihapus dari tabel customers.');
    }
}
