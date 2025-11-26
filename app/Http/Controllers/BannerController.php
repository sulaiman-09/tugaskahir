<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\BannersExport;

class BannerController extends Controller
{
    // Tampilkan semua banner (search + paginate)
    public function index(Request $request)
    {
        $q = $request->query('search');
        $perPage = $request->query('per_page', 15);
        $showAll = strtolower($perPage) === 'all';

        $query = Banner::query();

        // 🔍 Search
        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        // 🧾 Pagination / All
        if ($showAll) {
            $banners = $query->orderBy('created_at', 'desc')->get();
        } else {
            $banners = $query->orderBy('created_at', 'desc')
                ->paginate((int)$perPage)
                ->withQueryString();
        }

        return view('banner.index', compact('banners', 'perPage', 'q', 'showAll'));
    }

    // Tampilkan form tambah banner
    public function create()
    {
        return view('banner.create');
    }

    // Simpan banner baru ke database
    // Simpan banner baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'web_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $banner = new Banner();
        $banner->name = $validated['name'];
        $banner->is_active = $validated['is_active'];

        // Upload web image
        if ($request->hasFile('web_image')) {
            $path = $request->file('web_image')->store('banners', 'public');
            $banner->path = $path;
        }

        // Upload mobile image
        if ($request->hasFile('mobile_image')) {
            $path_apps = $request->file('mobile_image')->store('banners', 'public');
            $banner->path_apps = $path_apps;
        }

        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    // Edit banner
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        if (request()->ajax() || request()->query('modal')) {
            return view('banner.partials.form', [
                'banner' => $banner,
                'hideCancel' => true,
            ]);
        }

        return view('banner.edit', compact('banner'));
    }

    // Update banner
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'web_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $banner = Banner::findOrFail($id);
        $banner->name = $validated['name'];
        $banner->is_active = $validated['is_active'];

        // Jika user upload gambar baru untuk web
        if ($request->hasFile('web_image')) {
            $path = $request->file('web_image')->store('banners', 'public');
            $banner->path = $path;
        }

        // Jika user upload gambar baru untuk mobile
        if ($request->hasFile('mobile_image')) {
            $path_apps = $request->file('mobile_image')->store('banners', 'public');
            $banner->path_apps = $path_apps;
        }

        $banner->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('banner.index')->with('success', 'Banner berhasil diperbarui.');
    }

    // Hapus banner
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('banner.index')->with('success', 'Banner berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $q = $request->query('search');
        $query = Banner::query();

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $filename = 'banners_export_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new BannersExport($items), $filename);
    }

    public function exportPdf(Request $request)
    {
        $q = $request->query('search');
        $query = Banner::query();

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $html = view('banner.export-pdf', compact('items'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="banners_export_' . now()->format('Ymd_His') . '.pdf"');
    }
    public function toggleStatus(Request $request, Banner $banner)
    {
        $banner->is_active = $request->is_active;
        $banner->save();

        return response()->json([
            'success' => true,
            'is_active' => $banner->is_active,
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        Banner::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => count($ids) . ' banner berhasil dihapus.']);
    }
}
