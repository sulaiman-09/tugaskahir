<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // Tampilkan semua banner (search + paginate)
    public function index(Request $request)
    {
        $query = Banner::query();
        if ($q = $request->query('search')) {
            $query->where('name', 'like', "%{$q}%");
        }

        $banners = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('banner.index', compact('banners'));
    }

    // Tampilkan form tambah banner
    public function create()
    {
        return view('banner.create');
    }

    // Simpan banner baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'web_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        unset($validated['status']);

        if ($request->hasFile('web_image')) {
            $validated['web_image'] = $request->file('web_image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        // 🩹 Tambahkan baris ini (biar field path gak null)
        $validated['path'] = $validated['web_image'] ?? '';

        Banner::create($validated);

        return redirect()->route('banner.index')->with('success', 'Banner berhasil ditambahkan ke database.');
    }

    // Edit banner
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banner.edit', compact('banner'));
    }

    // Update banner
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'web_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        unset($validated['status']); // cegah error karena kolom tidak ada

        $banner = Banner::findOrFail($id);

        if ($request->hasFile('web_image')) {
            $validated['web_image'] = $request->file('web_image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('banner.index')->with('success', 'Banner berhasil diperbarui.');
    }

    // Hapus banner
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('banner.index')->with('success', 'Banner berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $q = $request->query('search');
        $query = Banner::query();

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $filename = 'banners_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Web Image', 'Mobile Image', 'Created At']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->name,
                    $i->web_image,
                    $i->mobile_image,
                    $i->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
