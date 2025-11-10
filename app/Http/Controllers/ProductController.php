<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $productSearch  = $request->input('product_search');
        $categorySearch = $request->input('category_search');
        $sort           = $request->input('sort', 'desc');
        $categoryQuery = ProductCategory::with('benefits');

        // Ambil nilai per-page dari form
        $categoryPerPage = $request->get('category_per_page', 15);
        $productPerPage  = $request->get('product_per_page', 15); // HARUS sesuai name form

        // =========================
        // 🔹 CATEGORY QUERY
        // =========================

        if ($categorySearch) {
            $categoryQuery->where('name', 'like', "%$categorySearch%")
                ->orWhere('slug', 'like', "%$categorySearch%");
        }

        if ($categoryPerPage === 'all') {
            $categories = $categoryQuery->orderBy('id', 'asc')->get();
        } else {
            $categories = $categoryQuery->orderBy('id', 'asc')
                ->paginate((int)$categoryPerPage)
                ->withQueryString();
        }

        // =========================
        // 🔹 PRODUCT QUERY
        // =========================
        $productQuery = Product::with('category')
            ->when($productSearch, function ($query) use ($productSearch) {
                $query->where('name', 'like', "%$productSearch%")
                    ->orWhere('speed', 'like', "%$productSearch%")
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%$productSearch%"));
            })
            ->orderBy('id', $sort);

        if ($productPerPage === 'all') {
            $products = $productQuery->get();
        } else {
            $products = $productQuery->paginate((int)$productPerPage)
                ->withQueryString();
        }

        return view('product.index', compact(
            'products',
            'categories',
            'sort',
            'productSearch',
            'categorySearch',
            'categoryPerPage',
            'productPerPage'
        ));
    }


    // ========== CATEGORY CREATE ==========
    public function createCategory()
    {
        return view('product.create_category');
    }

    // ========== CATEGORY STORE ==========
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories',
            'short_description' => 'nullable|string',
            'benefit' => 'nullable|string',
            'show_price' => 'nullable|boolean',
        ]);

        // Simpan kategori dulu
        $category = ProductCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
            'show_price' => $request->has('show_price') ? 1 : 0,
        ]);

        // Kalau ada benefit, simpan ke tabel product_benefits
        if ($request->filled('benefit')) {
            $benefitLines = preg_split('/\r\n|\r|\n/', $request->benefit);

            foreach ($benefitLines as $line) {
                if (trim($line) !== '') {
                    \App\Models\ProductBenefit::create([
                        'product_category_id' => $category->id,
                        'description' => trim($line),
                        'icon' => '',
                    ]);
                }
            }
        }

        return redirect()->route('product.index')->with('success', 'Category created successfully with benefits!');
    }


    // ========== CATEGORY EDIT ==========
    public function editCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('product.edit_category', compact('category'));
    }

    // ========== CATEGORY UPDATE ==========
    public function updateCategory(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . $category->id,
            'short_description' => 'nullable|string',
            'benefit' => 'nullable|string',
            'show_price' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
            'benefit' => $request->benefit,
            'show_price' => $request->has('show_price') ? 1 : 0,
        ]);

        return redirect()->route('product.index')->with('success', 'Category updated successfully!');
    }

    // ========== CATEGORY DELETE ==========
    public function destroyCategory($id)
    {
        ProductCategory::findOrFail($id)->delete();
        return redirect()->route('product.index')->with('success', 'Category deleted successfully!');
    }

    /* ======================================================
       ===============  BAGIAN B : PRODUCT CRUD  =============
       ====================================================== */

    // FORM CREATE PRODUK
    public function create()
    {
        $categories = ProductCategory::all();
        return view('product.create', compact('categories'));
    }

    public function create_Category()
    {
        return view('product.create_category');
    }

    // SIMPAN PRODUK BARU
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'description' => 'required|string',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'nullable|numeric',
            'web_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'apps_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Siapkan data untuk insert
        $data = [
            'name' => $validated['name'],
            'speed' => $validated['speed'],
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'],
            'price' => $validated['price'],
            'show_price' => 1, // default show price
        ];

        // Simpan web_image jika ada
        if ($request->hasFile('web_image')) {
            $data['web_image'] = $request->file('web_image')->store('products', 'public');
        }

        // Simpan apps_image → path_apps jika ada
        if ($request->hasFile('apps_image')) {
            $data['path_apps'] = $request->file('apps_image')->store('products', 'public');
        }

        // Simpan produk baru
        Product::create($data);

        return redirect()->route('product.index')->with('success', 'Product created successfully!');
    }


    // EDIT PRODUK
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();
        return view('product.edit', compact('product', 'categories'));
    }

    // UPDATE PRODUK
    public function update(Request $request, $id)
    {
        // Ambil data produk
        $product = Product::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'description' => 'required|string',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'nullable|numeric',
            'web_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'apps_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Siapkan data update
        $data = [
            'name' => $validated['name'],
            'speed' => $validated['speed'],
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'],
            'price' => $validated['price'],
        ];

        // Update Web Image jika ada file baru
        if ($request->hasFile('web_image')) {
            // Hapus file lama jika ada
            if ($product->web_image && Storage::disk('public')->exists($product->web_image)) {
                Storage::disk('public')->delete($product->web_image);
            }
            // Simpan file baru
            $data['web_image'] = $request->file('web_image')->store('products', 'public');
        }

        // Update Apps Image → simpan ke kolom path_apps
        if ($request->hasFile('apps_image')) {
            if ($product->path_apps && Storage::disk('public')->exists($product->path_apps)) {
                Storage::disk('public')->delete($product->path_apps);
            }
            $data['path_apps'] = $request->file('apps_image')->store('products', 'public');
        }

        // Update data produk
        $product->update($data);

        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }


    // HAPUS PRODUK
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->web_image) Storage::disk('public')->delete($product->web_image);
        if ($product->apps_image) Storage::disk('public')->delete($product->apps_image);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
    }

    public function toggleCategoryPrice($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->show_price = !$category->show_price;
        $category->save();

        return redirect()->route('product.index')->with('success', 'Status Show Price berhasil diperbarui.');
    }

    // HIDE/SHOW HARGA PRODUK
    public function togglePrice($id)
    {
        $product = Product::findOrFail($id);
        $product->show_price = !$product->show_price;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Harga produk diperbarui.');
    }

    // EXPORT PRODUCT KE CSV
    // ====================================
    // 🔹 EXPORT PRODUCT KE EXCEL
    // ====================================
    public function exportExcel(Request $request)
    {
        $search = $request->input('product_search');
        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('speed', 'like', "%$search%")
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%$search%"));
            })
            ->orderBy('id', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['ID', 'Name', 'Category', 'Speed', 'Price', 'Description', 'Show Price'], null, 'A1');

        $row = 2;
        foreach ($products as $p) {
            $sheet->fromArray([
                $p->id,
                $p->name,
                optional($p->category)->name,
                $p->speed,
                $p->price,
                strip_tags($p->description),
                $p->show_price ? 'Yes' : 'No',
            ], null, "A{$row}");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'products_' . now()->format('Ymd_His') . '.xlsx';

        // simpan ke output
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // ====================================
    // 🔹 EXPORT PRODUCT KE PDF
    // ====================================
    public function exportPdf(Request $request)
    {
        $search = $request->input('product_search');
        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('speed', 'like', "%$search%")
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%$search%"));
            })
            ->orderBy('id', 'desc')
            ->get();

        $pdf = Pdf::loadView('product.export-pdf', compact('products'));
        return $pdf->download('products_' . now()->format('Ymd_His') . '.pdf');
    }

    // ====================================
    // 🔹 EXPORT CATEGORY KE EXCEL
    // ====================================
    public function exportCategoryExcel(Request $request)
    {
        $search = $request->input('category_search');
        $categories = ProductCategory::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('slug', 'like', "%$search%");
        })
            ->orderBy('id', 'asc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['ID', 'Name', 'Slug', 'Short Description', 'Show Price'], null, 'A1');

        $row = 2;
        foreach ($categories as $c) {
            $sheet->fromArray([
                $c->id,
                $c->name,
                $c->slug,
                strip_tags($c->short_description),
                $c->show_price ? 'Yes' : 'No',
            ], null, "A{$row}");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'categories_' . now()->format('Ymd_His') . '.xlsx';
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // ====================================
    // 🔹 EXPORT CATEGORY KE PDF
    // ====================================
    public function exportCategoryPdf(Request $request)
    {
        $search = $request->input('category_search');
        $categories = ProductCategory::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('slug', 'like', "%$search%");
        })
            ->orderBy('id', 'asc')
            ->get();

        $pdf = Pdf::loadView('product.export-category-pdf', compact('categories'));
        return $pdf->download('product_categories_' . now()->format('Ymd_His') . '.pdf');
    }


    // Bulk delete Product
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No products selected.']);

        try {
            \App\Models\Product::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => count($ids) . ' products deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete products.']);
        }
    }

    // Bulk delete Category
    public function bulkDeleteCategory(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No categories selected.']);

        try {
            \App\Models\ProductCategory::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => count($ids) . ' categories deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete categories.']);
        }
    }
}
