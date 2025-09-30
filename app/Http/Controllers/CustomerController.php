<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Dummy data customer
    private $customers = [
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

    // INDEX + FILTER
    public function index(Request $request)
    {
        $customers = $this->customers;

        if ($request->has('filter')) {
            $today = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            $customers = array_filter($customers, function ($c) use ($request, $today, $yesterday) {
                switch ($request->filter) {
                    case 'today':
                        return $c['submitted_at'] == $today;
                    case 'yesterday':
                        return $c['submitted_at'] == $yesterday;
                    case 'last_7_days':
                        return strtotime($c['submitted_at']) >= strtotime('-7 days');
                    case 'last_30_days':
                        return strtotime($c['submitted_at']) >= strtotime('-30 days');
                    default:
                        return true;
                }
            });
        }

        return view('customer.index', compact('customers'));
    }

    // CREATE (Modal biasanya di blade, bisa dilewatkan)
    public function create()
    {
        return view('customer.create'); // opsional, kalau pakai modal cukup di index
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'coverage' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
        ]);

        // Simulasi insert dummy
        // Biasanya Customer::create($validated);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    // EDIT
    public function edit($id)
    {
        $customer = collect($this->customers)->firstWhere('id', $id);

        if (!$customer) {
            return redirect()->route('customer.index')->with('error', 'Customer tidak ditemukan.');
        }

        return view('customer.edit', compact('customer'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'coverage' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
        ]);

        // Ambil data dari session atau default dummy
        $customers = session('customers', $this->customers);

        // Update data sesuai ID
        foreach ($customers as &$customer) {
            if ($customer['id'] == $id) {
                $customer['name'] = $validated['name'];
                $customer['phone'] = $validated['phone'];
                $customer['email'] = $validated['email'];
                $customer['address'] = $validated['address'];
                $customer['coverage'] = $validated['coverage'] ?? $customer['coverage'];
                $customer['product'] = $validated['product'] ?? $customer['product'];
            }
        }
        unset($customer); // tambahkan ini supaya reference aman

        // Simpan kembali ke session
        session(['customers' => $customers]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui.');
    }

    // DESTROY
    public function destroy($id)
    {
        // Simulasi hapus dummy
        // Biasanya Customer::find($id)->delete();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
    }
}
