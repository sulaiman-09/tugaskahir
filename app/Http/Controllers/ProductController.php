<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    // Tampilkan semua data dari tabel product_categories
    public function index()
    {
        $product = ProductCategory::orderBy('created_at', 'desc')->get();
        return view('product.index', compact('product'));
    }

    // Tampilkan form tambah
    public function create()
    {
        return view('product.create');
    }

    // Simpan data baru ke tabel product_categories
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug',
            'short_description' => 'nullable|string',
            'show_price' => 'nullable|boolean',
        ]);

        ProductCategory::create($validated);

        return redirect()->route('product.index')
            ->with('success', 'Kategori produk berhasil ditambahkan ke database.');
    }

    // Edit kategori
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('product.edit', compact('category'));
    }

    // Update kategori
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . $id,
            'short_description' => 'nullable|string',
            'show_price' => 'nullable|boolean',
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update($validated);

        return redirect()->route('product.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    // Hapus kategori
    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('product.index')
            ->with('success', 'Kategori produk berhasil dihapus.');
    }
}
