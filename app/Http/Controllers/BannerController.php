<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // Tampilkan semua banner
    public function index()
    {
        $banners = Banner::orderBy('created_at', 'desc')->get();
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
            'status' => 'nullable|boolean',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('web_image')) {
            $validated['web_image'] = $request->file('web_image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

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
            'status' => 'nullable|boolean',
        ]);

        $banner = Banner::findOrFail($id);

        // Upload gambar baru jika ada
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
}
