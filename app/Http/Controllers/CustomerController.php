<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ArrayExport;
use App\Models\Division;


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

        // Filter tanggal
        $filter = strtolower($request->query('filter', 'all'));
        $today = Carbon::today();

        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', $today);
                break;
            case 'yesterday':
                $query->whereDate('created_at', $today->copy()->subDay());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [
                    $today->copy()->startOfWeek(),
                    $today->copy()->endOfWeek()->endOfDay(),
                ]);
                break;
            case 'last_week':
                $query->whereBetween('created_at', [
                    $today->copy()->subWeek()->startOfWeek(),
                    $today->copy()->subWeek()->endOfWeek()->endOfDay(),
                ]);
                break;
            case 'this_month':
                $query->whereBetween('created_at', [
                    $today->copy()->startOfMonth(),
                    $today->copy()->endOfMonth()->endOfDay(),
                ]);
                break;
            case 'last_month':
                $query->whereBetween('created_at', [
                    $today->copy()->subMonth()->startOfMonth(),
                    $today->copy()->subMonth()->endOfMonth()->endOfDay(),
                ]);
                break;
            case 'last_7_days':
                $query->whereBetween('created_at', [
                    $today->copy()->subDays(6),
                    $today->copy()->endOfDay(),
                ]);
                break;
            case 'last_30_days':
                $query->whereBetween('created_at', [
                    $today->copy()->subDays(29),
                    $today->copy()->endOfDay(),
                ]);
                break;
            case 'custom':
                $fromRaw = $request->query('from');
                $toRaw = $request->query('to');

                $from = $fromRaw && Carbon::hasFormat($fromRaw, 'Y-m-d')
                    ? Carbon::createFromFormat('Y-m-d', $fromRaw)->startOfDay()
                    : null;
                $to = $toRaw && Carbon::hasFormat($toRaw, 'Y-m-d')
                    ? Carbon::createFromFormat('Y-m-d', $toRaw)->endOfDay()
                    : null;

                if ($from && $to) {
                    $query->whereBetween('created_at', [$from, $to]);
                } elseif ($from) {
                    $query->where('created_at', '>=', $from);
                } elseif ($to) {
                    $query->where('created_at', '<=', $to);
                }
                break;
            default:
                // 'all' or unknown: no date filter
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        if (strtolower($perPage) === 'all') {
            $customer_leads = $query->orderBy('created_at', 'desc')->get();
        } else {
            $customer_leads = $query->orderBy('created_at', 'desc')
                ->paginate((int)$perPage)
                ->withQueryString();
        }

        // juga kirim products dan categories agar modal edit bisa memakai opsi yang sama
        $products = Product::all();
        $categories = ProductCategory::all();

        return view('customer.index', compact('customer_leads', 'perPage', 'products', 'categories'));
    }


    /**
     * Form tambah customer baru.
     */
public function create()
{
    // ambil semua data dari tabel
    $products = Product::all();
    $categories = ProductCategory::all();
    $divisions = Division::all(); // 🔥 wajib ditambahkan

    // kirim ke view
    return view('customer.create', [
        'products' => $products,
        'categories' => $categories,
        'divisions' => $divisions
    ]);
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
            'division_id'       => 'nullable|exists:divisions,id',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_id'          => 'nullable|exists:products,id',
            'latitude'          => 'nullable|string|max:255',
            'longitude'         => 'nullable|string|max:255',
            'coverage'          => 'nullable|string|max:255',
        ]);

        // Ensure region_id is set to null if not provided
        $validated['region_id'] = $validated['region_id'] ?? null;

        // Isi customer_address dengan address jika kosong, agar Maps punya alamat
        if (empty($validated['customer_address']) && !empty($validated['address'])) {
            $validated['customer_address'] = $validated['address'];
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

        // jika request AJAX, kembalikan JSON agar modal dapat mengisi field
        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'customer' => $customer,
                'products' => $products,
                'categories' => $categories,
            ]);
        }

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
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_id'          => 'nullable|exists:products,id',
            'latitude'          => 'nullable|string|max:255',
            'longitude'         => 'nullable|string|max:255',
            'coverage'          => 'nullable|string|max:255',
        ]);

        // Ensure region_id is set to null if not provided
        $validated['region_id'] = $validated['region_id'] ?? null;

        if (empty($validated['customer_address']) && !empty($validated['address'])) {
            $validated['customer_address'] = $validated['address'];
        }

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Data customer berhasil diperbarui.']);
        }

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

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids; // array of customer IDs

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih.'
            ]);
        }

        try {
            \App\Models\Customer::whereIn('id', $ids)->delete(); // Hapus data
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' data customer berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        // Urutkan dari data paling awal agar export dimulai dari nomor 1
        $customers = Customer::with(['product', 'productCategory'])
            ->orderBy('created_at', 'asc')
            ->get();

        $data = $customers->map(function ($c) {
            return [
                'ID' => $c->id,
                'Customer Name' => $c->customer_name,
                'Phone' => $c->customer_phone,
                'Email' => $c->email,
                'Address' => $c->address,
                'Product' => $c->product->product_name ?? '-',
                'Category' => $c->productCategory->name ?? '-',
                'Created At' => $c->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return Excel::download(new ArrayExport($data), 'customers_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        // Urutkan dari data paling awal agar PDF dimulai dari nomor 1
        $customers = Customer::with(['product', 'productCategory'])
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('customer.export-pdf', compact('customers'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('customers_' . now()->format('Ymd_His') . '.pdf');
    }
}
