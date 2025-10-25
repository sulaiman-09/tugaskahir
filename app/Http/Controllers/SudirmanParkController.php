<?php

namespace App\Http\Controllers;

use App\Models\SudirmanPark;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\SudirmanTowerAddress;
use Illuminate\Support\Facades\Response;

class SudirmanParkController extends Controller
{
    public function index()
    {
        $q = request('q');
        $showAll = request('show_all') == '1';

        $query = SudirmanPark::query();
        $hasVisible = Schema::hasColumn('sudirman_parks', 'visible');
        if (!$showAll && $hasVisible) {
            // default only visible when column exists
            $query->where('visible', true);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('ktp', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('sudirmanpark.index', compact('customers', 'q', 'showAll'));
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
                $path = $file->storeAs('public/ktp', $filename);
                // store only filename (or use Storage::url later in views)
                $validated['ktp'] = $filename;
                \Log::info('KTP uploaded for new customer: ' . $filename . ' stored at ' . $path);
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
        return view('sudirmanpark.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
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
                Storage::delete('public/ktp/' . $customer->ktp);
            }
            try {
                $file = $request->file('ktp');
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/ktp', $filename);
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
            $customer->update($validated);
        } catch (\Throwable $e) {
            \Log::error('SudirmanPark update failed for id ' . $customer->id . ': ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal memperbarui customer. Silakan cek log.']);
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
        // prefer using the public disk explicitly and check existence
        $disk = Storage::disk('public');
        $original = $customer->ktp;
        $candidates = [];
        if ($original) {
            $candidates[] = $original;
            $candidates[] = urldecode($original);
            $candidates[] = basename($original);
        }

        // also try empty candidate (will be skipped)
        $found = false;
        $checked = [];
        foreach (array_unique($candidates) as $c) {
            if (!$c) continue;
            $path = 'ktp/' . $c;
            // check via Storage disk
            $existsDisk = $disk->exists($path);
            $fsPath = storage_path('app/public/ktp/' . $c);
            $pubPath = public_path('storage/ktp/' . $c);
            $checked[$c] = [
                'disk_exists' => $existsDisk,
                'fs_exists' => is_file($fsPath) && is_readable($fsPath),
                'pub_exists' => is_file($pubPath) && is_readable($pubPath),
                'disk_path' => $path,
                'fs_path' => $fsPath,
                'pub_path' => $pubPath,
            ];

            if ($existsDisk) {
                try {
                    return $disk->download($path);
                } catch (\Throwable $e) {
                    \Log::error('KTP download failed for ' . $path . ': ' . $e->getMessage());
                    try {
                        $stream = $disk->readStream($path);
                        if ($stream) {
                            return response()->stream(function () use ($stream) {
                                fpassthru($stream);
                            }, 200, [
                                'Content-Type' => $disk->mimeType($path) ?? 'application/octet-stream',
                                'Content-Disposition' => 'attachment; filename="' . basename($path) . '"',
                            ]);
                        }
                    } catch (\Throwable $e2) {
                        \Log::error('KTP fallback stream failed for ' . $path . ': ' . $e2->getMessage());
                    }
                }
            }

            // check direct filesystem path (storage/app/public/ktp)
            // check direct filesystem path (storage/app/public/ktp)
            if (is_file($fsPath) && is_readable($fsPath)) {
                return response()->download($fsPath, basename($fsPath));
            }

            // check public path (public/storage/ktp)
            if (is_file($pubPath) && is_readable($pubPath)) {
                return response()->download($pubPath, basename($pubPath));
            }
        }
        \Log::warning("KTP download requested but none of candidate filenames found for customer id: {$customer->id}", ['candidates' => $candidates, 'checked' => $checked, 'db_value' => $original]);
        abort(404, 'File KTP tidak ditemukan.');
    }

    /**
     * Preview KTP inline (for modal) - returns file response with inline disposition
     */
    public function previewKtp($id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $disk = Storage::disk('public');
        $original = $customer->ktp;
        $candidates = [];
        if ($original) {
            $candidates[] = $original;
            $candidates[] = urldecode($original);
            $candidates[] = basename($original);
        }
        $checked = [];
        foreach (array_unique($candidates) as $c) {
            if (!$c) continue;
            $path = 'ktp/' . $c;

            $existsDisk = $disk->exists($path);
            $fsPath = storage_path('app/public/ktp/' . $c);
            $pubPath = public_path('storage/ktp/' . $c);

            $checked[$c] = [
                'disk_exists' => $existsDisk,
                'fs_exists' => is_file($fsPath) && is_readable($fsPath),
                'pub_exists' => is_file($pubPath) && is_readable($pubPath),
                'disk_path' => $path,
                'fs_path' => $fsPath,
                'pub_path' => $pubPath,
            ];

            // Storage disk check
            if ($existsDisk) {
                try {
                    if (method_exists($disk, 'path')) {
                        $local = $disk->path($path);
                        return response()->file($local, ['Content-Disposition' => 'inline; filename="' . basename($path) . '"']);
                    }

                    $stream = $disk->readStream($path);
                    if ($stream) {
                        return response()->stream(function () use ($stream) {
                            fpassthru($stream);
                        }, 200, [
                            'Content-Type' => $disk->mimeType($path) ?? 'application/octet-stream',
                            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
                        ]);
                    }
                } catch (\Throwable $e) {
                    \Log::error('KTP preview failed for ' . $path . ': ' . $e->getMessage());
                }
            }

            // direct filesystem checks
            if (is_file($fsPath) && is_readable($fsPath)) {
                return response()->file($fsPath, ['Content-Disposition' => 'inline; filename="' . basename($fsPath) . '"']);
            }

            if (is_file($pubPath) && is_readable($pubPath)) {
                return response()->file($pubPath, ['Content-Disposition' => 'inline; filename="' . basename($pubPath) . '"']);
            }
        }

        \Log::warning("KTP preview requested but none of candidate filenames found for customer id: {$customer->id}", ['candidates' => $candidates, 'checked' => $checked, 'db_value' => $original]);
        abort(404, 'File KTP tidak ditemukan.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $customer->status = $request->input('status', $customer->status == 'approved' ? 'registration' : 'approved');
        $customer->save();
        return response()->json(['status' => $customer->status]);
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
            $query->where('tower', 'like', "%{$q}%")
                ->orWhere('floor', 'like', "%{$q}%")
                ->orWhere('unit', 'like', "%{$q}%")
                ->orWhere('full_address', 'like', "%{$q}%");
        }

        $addresses = $query->latest()->paginate(10)->withQueryString();

        return view('sudirmanpark.alamat', compact('addresses', 'q', 'showAll'));
    }


    public function updateStatus(Request $request, $id)
    {
        $customer = SudirmanPark::findOrFail($id);
        $customer->status = $request->status;
        $customer->save();

        return response()->json(['success' => true]);
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
}
