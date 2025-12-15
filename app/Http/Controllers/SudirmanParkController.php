<?php

namespace App\Http\Controllers;

use App\Models\SudirmanPark;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\SudirmanTowerAddress;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SudirmanParkExport;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SudirmanParkController extends Controller
{
    public function index(Request $request)
    {
        $q       = $request->query('q');
        $perPage = $request->query('per_page', 15);
        $showAll = strtolower($perPage) === 'all';
        $showVisibleOnly = $request->query('show_all') != '1';

        $query = SudirmanPark::query();

        // Cek kolom 'visible'
        if (Schema::hasColumn('sudirman_parks', 'visible') && $showVisibleOnly) {
            $query->where('visible', true);
        }

        // 🔍 Search
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('ktp', 'like', "%{$q}%");
            });
        }

        // 🧾 Pagination / All
        if ($showAll) {
            $customers = $query->latest()->get();
        } else {
            $customers = $query->latest()
                ->paginate((int)$perPage)
                ->withQueryString();
        }

        return view('sudirmanpark.index', compact('customers', 'q', 'perPage', 'showAll'));
    }

    public function create()
    {
        // Ambil daftar paket (product) dan daftar tower untuk dropdown
        $products = Product::orderBy('name')->get();

        // Ambil alamat tower yang aktif, urut berdasarkan full_address
        $addresses = SudirmanTowerAddress::where('is_active', 1)
            ->orderBy('full_address', 'asc')
            ->pluck('full_address', 'id');

        return view('sudirmanpark.create', compact('products', 'addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tower' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'payment_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('ktp', $file, $filename);
                $validated['ktp'] = $filename;
                \Log::info('KTP uploaded for new customer: ' . $filename);
            } catch (\Exception $e) {
                \Log::error('KTP upload failed: ' . $e->getMessage());
                return back()->withErrors(['ktp' => 'Gagal menyimpan file KTP. Silakan cek permission/storage link.']);
            }
        }

        // Simpan file bukti pembayaran (jika ada) ke disk publik, tapi jangan simpan nama file ke DB
        if ($request->hasFile('payment_proof')) {
            try {
                $file = $request->file('payment_proof');
                $paymentFilename = time() . '_payment_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('payments', $file, $paymentFilename);
                \Log::info('Bukti pembayaran di-upload: ' . $paymentFilename);
                // Jika nanti ingin menyimpan nama file di DB, tambahkan kolom dan simpan di validated
            } catch (\Exception $e) {
                \Log::error('Upload bukti pembayaran gagal: ' . $e->getMessage());
                return back()->withErrors(['payment_proof' => 'Gagal menyimpan file bukti pembayaran.']);
            }
        }

        // Only set visible if the column exists in DB (migration may not have run)
        if (Schema::hasColumn('sudirman_parks', 'visible')) {
            $validated['visible'] = $request->has('visible') ? true : true; // default visible
        }

        SudirmanPark::create($validated);

        return redirect()->route('sudirmanpark.index')
            ->with('success', 'Customer baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        // Jika request AJAX, kembalikan JSON untuk modal edit
        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json($customer);
        }

        return view('sudirmanpark.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        Log::info('SudirmanPark update START for id: ' . $id);
        Log::info('Request method: ' . $request->method() . ', data: ' . json_encode($request->all()));
        $customer = SudirmanPark::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tower' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('ktp')) {
            // delete old file if exists
            if ($customer->ktp) {
                Storage::disk('public')->delete('ktp/' . $customer->ktp);
            }
            try {
                $file = $request->file('ktp');
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('ktp', $file, $filename);
                $validated['ktp'] = $filename;
                \Log::info('KTP uploaded for customer update: ' . $filename);
            } catch (\Exception $e) {
                \Log::error('KTP upload failed (update): ' . $e->getMessage());
                return back()->withErrors(['ktp' => 'Gagal menyimpan file KTP. Silakan cek permission/storage link.']);
            }
        }

        // Only update visible when column exists
        if (Schema::hasColumn('sudirman_parks', 'visible')) {
            $validated['visible'] = $request->has('visible') ? true : false;
        }

        try {
            \Log::info('Updating SudirmanPark id ' . $customer->id . ' with data: ' . json_encode($validated));
            $customer->update($validated);
            \Log::info('SudirmanPark updated successfully for id ' . $customer->id);
        } catch (\Throwable $e) {
            \Log::error('SudirmanPark update failed for id ' . $customer->id . ': ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui customer. Silakan cek log.'], 500);
            }
            return back()->withErrors(['error' => 'Gagal memperbarui customer. Silakan cek log.']);
        }

        // Jika AJAX, kembalikan response JSON supaya frontend modal dapat menutup dan reload
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Customer berhasil diperbarui.']);
        }

        return redirect()->route('sudirmanpark.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        if ($customer->ktp) {
            Storage::delete('public/ktp/' . $customer->ktp);
        }
        $customer->delete();
        return back()->with('success', 'Customer dihapus.');
    }

    /**
     * Remove uploaded KTP file for a customer
     */
    public function removeKtp($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        if ($customer->ktp && Storage::exists('public/ktp/' . $customer->ktp)) {
            Storage::delete('public/ktp/' . $customer->ktp);
        }
        $customer->ktp = null;
        $customer->save();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'File KTP berhasil dihapus.');
    }

    /**
     * Download KTP file through controller to avoid direct file access issues
     */
    public function downloadKtp($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        if (!$customer->ktp) {
            abort(404, 'Customer tidak memiliki file KTP.');
        }

        $disk = Storage::disk('public');
        $path = 'ktp/' . $customer->ktp;

        if ($disk->exists($path)) {
            try {
                return $disk->download($path);
            } catch (\Throwable $e) {
                \Log::error("KTP download failed for path: {$path}. Error: " . $e->getMessage());
                abort(500, 'Gagal mengunduh file.');
            }
        }

        \Log::warning("KTP download requested but file not found for customer id: {$customer->id}. Path: {$path}");
        abort(404, 'File KTP tidak ditemukan di storage.');
    }

    /**
     * Preview KTP inline (for modal) - returns file response with inline disposition
     */
    public function previewKtp($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        if (!$customer->ktp) {
            abort(404, 'File KTP tidak ditemukan.');
        }

        $disk = Storage::disk('public');
        $path = 'ktp/' . $customer->ktp;

        if (!$disk->exists($path)) {
            \Log::warning("KTP preview requested but file not found on disk for customer id: {$customer->id}. Path: {$path}");
            abort(404, 'File KTP tidak ditemukan di storage.');
        }

        try {
            // Dapatkan path absolut dari storage
            $absolutePath = $disk->path($path);

            // Gunakan response()->file() untuk mengirim file.
            // Ini secara otomatis menangani header seperti Content-Type dan Content-Length.
            return response()->file($absolutePath);
        } catch (\Throwable $e) {
            \Log::error("KTP preview failed for path: {$path}. Error: " . $e->getMessage());
            abort(500, 'Gagal menampilkan file karena kesalahan server.');
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $customer->status = $request->input('status', $customer->status == 'approved' ? 'registration' : 'approved');
        $customer->status_change = 'Updated by ' . auth()->user()->name . ' on ' . now()->toDateTimeString();
        $customer->save();

        return response()->json(['status' => $customer->status, 'status_change' => $customer->status_change]);
    }

    // 🟢 Export Excel
    public function exportExcel(Request $request)
    {
        $filename = 'sudirmanpark_' . now()->format('Ymd_His') . '.xlsx';
        $query = SudirmanPark::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $data = $query->latest()->get();

        return Excel::download(new SudirmanParkExport($data), $filename);
    }

    // 🔴 Export PDF
    public function exportPdf(Request $request)
    {
        $query = SudirmanPark::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->get();

        $pdf = Pdf::loadView('sudirmanpark.export-pdf', compact('customers'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('sudirmanpark_' . now()->format('Ymd_His') . '.pdf');
    }


    public function alamat(Request $request)
    {
        $q = $request->q;
        $showAll = $request->query('show_all') == '1';

        $query = SudirmanTowerAddress::query();

        // Fitur search
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('tower', 'like', '%' . $q . '%')
                    ->orWhere('floor', 'like', '%' . $q . '%')
                    ->orWhere('unit', 'like', '%' . $q . '%')
                    ->orWhere('full_address', 'like', '%' . $q . '%');
            });
        }


        $perPage = $request->query('per_page', 10);
        $showAll = strtolower($perPage) === 'all';

        if ($showAll) {
            $addresses = $query->latest()->get();
        } else {
            $addresses = $query->latest()
                ->paginate((int)$perPage)
                ->withQueryString();
        }

        return view('sudirmanpark.alamat', compact('addresses', 'q', 'perPage', 'showAll'));
    }


    public function updateStatus(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $customer->status = $request->status;
        $customer->status_change = 'Updated by ' . auth()->user()->name . ' on ' . now()->toDateTimeString();
        $customer->save();

        return response()->json(['success' => true, 'status' => $customer->status, 'status_change' => $customer->status_change]);
    }

    public function createHomepass()
    {
        return view('sudirmanpark.createHomepass');
    }

    public function storeHomepass(Request $request)
    {
        $request->validate([
            'tower' => 'required|string|max:10',
            'floor' => 'required|string|max:10',
            'unit' => 'required|string|max:10',
            'status' => 'required|string',
        ]);

        // Buat alamat lengkap otomatis
        $alamatLengkap = strtoupper($request->tower . '-' . $request->floor . '-' . $request->unit);

        // Simpan ke database — full_address is a generated column in DB, so don't set it manually
        $data = [
            'tower' => strtoupper($request->tower),
            'floor' => strtoupper($request->floor),
            'unit' => strtoupper($request->unit),
            // convert status string to boolean is_active; treat 'Aktif' as true
            'is_active' => (strtolower($request->status) === 'aktif' || strtolower($request->status) === 'active') ? 1 : 0,
        ];

        try {
            $address = SudirmanTowerAddress::create($data);
        } catch (\Throwable $e) {
            \Log::error('storeHomepass failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan homepass: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Gagal menyimpan homepass. Silakan cek log.']);
        }

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'address' => $address], 201);
        }

        return redirect()->route('sudirmanpark.alamat')->with('success', 'Homepass baru berhasil ditambahkan!');
    }

    public function editHomepass($id)
    {
        $address = SudirmanTowerAddress::findOrFail($id);
        return view('sudirmanpark.editHomepass', compact('address'));
    }

    public function updateHomepass(Request $request, $id)
    {
        $request->validate([
            'tower' => 'required|string|max:10',
            'floor' => 'required|string|max:10',
            'unit' => 'required|string|max:10',
            'status' => 'required|string',
        ]);

        $address = SudirmanTowerAddress::findOrFail($id);
        $alamatLengkap = strtoupper($request->tower . '-' . $request->floor . '-' . $request->unit);

        $data = [
            'tower' => strtoupper($request->tower),
            'floor' => strtoupper($request->floor),
            'unit' => strtoupper($request->unit),
            'is_active' => (strtolower($request->status) === 'aktif' || strtolower($request->status) === 'active') ? 1 : 0,
        ];

        try {
            $address->update($data);
        } catch (\Throwable $e) {
            \Log::error('updateHomepass failed: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui homepass: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Gagal memperbarui homepass. Silakan cek log.']);
        }

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'address' => $address]);
        }

        return redirect()->route('sudirmanpark.alamat')->with('success', 'Homepass berhasil diperbarui!');
    }

    public function destroyHomepass($id)
    {
        $address = SudirmanTowerAddress::findOrFail($id);
        try {
            $address->delete();
        } catch (\Throwable $e) {
            \Log::error('destroyHomepass failed: ' . $e->getMessage());
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus homepass: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Gagal menghapus homepass. Silakan cek log.']);
        }

        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('sudirmanpark.alamat')->with('success', 'Homepass berhasil dihapus!');
    }

    public function exportHomepassExcel(Request $request)
    {
        $query = SudirmanTowerAddress::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('tower', 'like', "%{$search}%")
                ->orWhere('floor', 'like', "%{$search}%")
                ->orWhere('unit', 'like', "%{$search}%")
                ->orWhere('full_address', 'like', "%{$search}%");
        }

        $homepasses = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['Tower', 'Floor', 'Unit', 'Alamat Lengkap', 'Jumlah Customer', 'Status', 'Tanggal Dibuat'], null, 'A1');

        $row = 2;
        foreach ($homepasses as $h) {
            $sheet->fromArray([
                $h->tower,
                $h->floor,
                $h->unit,
                $h->full_address,
                $h->jumlah_customer ?? 0,
                ($h->is_active ? 'Aktif' : 'Nonaktif'),
                $h->created_at->format('d/m/Y H:i:s'),
            ], null, 'A' . $row);
            $row++;
        }

        $filename = 'homepass_export_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    public function exportHomepassPdf(Request $request)
    {
        $query = SudirmanTowerAddress::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('tower', 'like', "%{$search}%")
                ->orWhere('floor', 'like', "%{$search}%")
                ->orWhere('unit', 'like', "%{$search}%")
                ->orWhere('full_address', 'like', "%{$search}%");
        }

        $homepasses = $query->limit(200)->get(); // ambil maksimal 200 data dulu biar gak overload

        $pdf = Pdf::loadView('sudirmanpark.export-homepass-pdf', compact('homepasses'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('homepass_export_' . now()->format('Ymd_His') . '.pdf');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        try {
            Customer::whereIn('id', $ids)->delete(); // langsung hapus permanen
            return response()->json(['success' => true, 'message' => count($ids) . ' customer berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus.']);
        }
    }

    public function bulkDeleteCustomer(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada customer yang dipilih.',
            ], 422);
        }

        try {
            $deleted = SudirmanPark::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $deleted . ' customer berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Bulk delete customer Sudirman gagal', [
                'ids'   => $ids,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus customer.',
            ], 500);
        }
    }

}
