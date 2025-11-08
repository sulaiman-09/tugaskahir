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
use Illuminate\Support\Facades\Response;

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
        return view('sudirmanpark.create');
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

    public function export(Request $request): StreamedResponse
    {
        $q = $request->query('q');
        $showAll = $request->query('show_all') == '1';

        $query = SudirmanPark::query();
        $hasVisible = Schema::hasColumn('sudirman_parks', 'visible');
        if (!$showAll && $hasVisible) {
            $query->where('visible', true);
        }
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $items = $query->latest()->get();

        $filename = 'sudirmanpark_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Tower', 'Package', 'KTP', 'Status', 'Visible', 'Created At']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->name,
                    $i->phone,
                    $i->email,
                    $i->tower,
                    $i->package,
                    $i->ktp ? asset('storage/ktp/' . $i->ktp) : '',
                    $i->status,
                    ($hasVisible ? ($i->visible ? '1' : '0') : ''),
                    $i->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
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
            if ($request->ajax() || $request->wentsJson() || $request->expectsJson()) {
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

    public function exportHomepass(Request $request)
    {
        $query = SudirmanTowerAddress::query(); // pakai model yang benar

        // Fitur search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('tower', 'like', "%{$search}%")
                ->orWhere('floor', 'like', "%{$search}%")
                ->orWhere('unit', 'like', "%{$search}%")
                ->orWhere('full_address', 'like', "%{$search}%");
        }

        $homepasses = $query->get();

        $csvHeader = ['Tower', 'Floor', 'Unit', 'Alamat Lengkap', 'Jumlah Customer', 'Status', 'Tanggal Dibuat'];
        $filename = 'homepass_export_' . date('Ymd_His') . '.csv';

        $callback = function () use ($homepasses, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($homepasses as $h) {
                fputcsv($file, [
                    $h->tower,
                    $h->floor,
                    $h->unit,
                    $h->full_address,
                    $h->jumlah_customer ?? 0,
                    ($h->is_active ? 'Aktif' : 'Nonaktif'),
                    $h->created_at->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}"
        ]);
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

    public function bulkDeleteHomepass(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        try {
            SudirmanTowerAddress::whereIn('id', $ids)->delete(); // bisa soft delete kalau model pakai SoftDeletes
            return response()->json(['success' => true, 'message' => count($ids) . ' alamat berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus.']);
        }
    }
}
