<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    // Tampilkan semua data dari tabel product_categories
    public function index(Request $request)
    {
        $query = ProductCategory::query();
        if ($q = $request->query('search')) {
            $query->where('category_name', 'like', "%{$q}%");
        }

        $product = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('product.index', compact('product'));
    }

    public function export(Request $request)
    {
        $q = $request->query('search');
        $query = ProductCategory::query();
        if ($q) $query->where('category_name', 'like', "%{$q}%");
        $items = $query->orderBy('created_at', 'desc')->get();

        $filename = 'product_categories_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Category Name','Slug','Short Description','Show Price','Created At']);
            foreach ($items as $i) {
                fputcsv($out, [
                    $i->id,
                    $i->category_name,
                    $i->slug,
                    $i->short_description,
                    $i->show_price,
                    $i->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
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
