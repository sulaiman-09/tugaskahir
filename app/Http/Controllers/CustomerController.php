<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar lead/customer.
     */
    public function index(Request $request)
{
    $query = Customer::with(['product', 'productCategory']);

    // Search
    if ($q = $request->query('search')) {
        $query->where(function ($sub) use ($q) {
            $sub->where('customer_name', 'like', '%' . $q . '%')
                ->orWhere('customer_phone', 'like', '%' . $q . '%')
                ->orWhere('email', 'like', '%' . $q . '%')
                ->orWhere('address', 'like', '%' . $q . '%');
        });
    }

    // Filter tanggal (opsional sama seperti sebelumnya)

    // Pagination
    $perPage = $request->get('per_page', 10);
    if (strtolower($perPage) === 'all') {
        $customer_leads = $query->orderBy('created_at', 'desc')->get();
    } else {
        $customer_leads = $query->orderBy('created_at', 'desc')
            ->paginate((int)$perPage)
            ->withQueryString();
    }

    return view('customer.index', compact('customer_leads', 'perPage'));
    }


    /**
     * Form tambah customer baru.
     */
    public function create()
    {
         // ambil semua data dari tabel products & product_categories
        $products = Product::all();
        $categories = ProductCategory::all();

        // kirim ke view
        return view('customer.create', compact('products', 'categories'));
    }

    /**
     * Simpan customer baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_phone'    => 'required|string|max:20',
            'email'             => 'nullable|email|max:255',
            'address'           => 'required|string',
            'customer_address'  => 'nullable|string|max:255',
            'referral_code'     => 'nullable|string|max:255',
            'province'          => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'district'          => 'nullable|string|max:255',
            'village'           => 'nullable|string|max:255',
            'region_id'         => 'nullable|string',
            'division'          => 'nullable|string|max:255',
            'product_category'  => 'nullable|string|max:255',
            'product'           => 'nullable|numeric',
            'latitude'          => 'nullable|string|max:255',
            'longitude'         => 'nullable|string|max:255',
            'coverage'          => 'nullable|string|max:255',
        ]);

        // Ensure region_id is set to null if not provided
        $validated['region_id'] = $validated['region_id'] ?? null;

        // Map product to product_id if it's numeric
        if (isset($validated['product']) && is_numeric($validated['product'])) {
            $validated['product_id'] = $validated['product'];
            unset($validated['product']);
        }

        Customer::create($validated);

        return redirect()->route('customer.index')
                         ->with('success', 'Customer baru berhasil ditambahkan.');
    }

    /**
     * Form edit customer.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $products = Product::all();
        $categories = ProductCategory::all();

        return view('customer.edit', compact('customer', 'products', 'categories'));
    }

    /**
     * Update data customer.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_phone'    => 'required|string|max:20',
            'email'             => 'nullable|email|max:255',
            'address'           => 'required|string',
            'customer_address'  => 'nullable|string|max:255',
            'referral_code'     => 'nullable|string|max:255',
            'province'          => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'district'          => 'nullable|string|max:255',
            'village'           => 'nullable|string|max:255',
            'region_id'         => 'nullable|string',
            'division'          => 'nullable|string|max:255',
            'product_category'  => 'nullable|string|max:255',
            'product'           => 'nullable|numeric',
            'latitude'          => 'nullable|string|max:255',
            'longitude'         => 'nullable|string|max:255',
            'coverage'          => 'nullable|string|max:255',
        ]);

        // Ensure region_id is set to null if not provided
        $validated['region_id'] = $validated['region_id'] ?? null;

        // Map product to product_id if it's numeric
        if (isset($validated['product']) && is_numeric($validated['product'])) {
            $validated['product_id'] = $validated['product'];
            unset($validated['product']);
        }

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return redirect()->route('customer.index')
                         ->with('success', 'Data customer berhasil diperbarui.');
    }

    /**
     * Hapus data customer.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customer.index')
                         ->with('success', 'Data customer berhasil dihapus.');
    }
}
