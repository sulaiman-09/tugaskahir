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

        // 🔍 Search
        if ($q = $request->query('search')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('product', 'like', "%{$q}%");
            });
        }

        // 📅 Filter tanggal
        if ($request->has('filter')) {
            $today     = date('Y-m-d');
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

        // 🧾 Records per page
        $perPage = $request->get('per_page', 15);

        if (strtolower($perPage) === 'all') {
            // Jika pilih 'All', ambil semua data
            $customers = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Pastikan di-cast ke integer agar pagination berjalan
            $customers = $query->orderBy('created_at', 'desc')
                ->paginate((int)$perPage)
                ->withQueryString();
        }

        return view('customer.index', compact('customers', 'perPage'));
    }

    // Export CSV of filtered customers
    public function export(Request $request)
    {
        $q = $request->query('search');
        $query = Customer::query();
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }
        $items = $query->orderBy('created_at', 'desc')->get();

        $filename = 'customers_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Address', 'Product', 'Coverage', 'Assign To', 'Submitted At', 'Created At']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->name,
                    $i->phone,
                    $i->email,
                    $i->address,
                    $i->product,
                    $i->coverage,
                    $i->assign_to,
                    $i->submitted_at,
                    $i->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
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
