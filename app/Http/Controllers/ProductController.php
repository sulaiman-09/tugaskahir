<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /* ======================================================
       ===============  BAGIAN A : CATEGORY CRUD  ============
       ====================================================== */

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'desc');

        // Produk
        $products = Product::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->orderBy('id', $sort)
            ->get();

        // Kategori
        $categories = ProductCategory::orderBy('id', 'asc')->get();

        return view('product.index', compact('products', 'categories', 'sort', 'search'));
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
            'show_price' => 'nullable|boolean',
        ]);

        ProductCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
            'show_price' => $request->has('show_price') ? 1 : 0,
        ]);

        return redirect()->route('product.index')->with('success', 'Category created successfully!');
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
            'show_price' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'short_description' => $request->short_description,
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
        $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'description' => 'required|string',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'nullable|numeric',
            'web_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'apps_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->only(['name', 'speed', 'description', 'product_category_id', 'price']);
        $data['show_price'] = 1;

        if ($request->hasFile('web_image')) {
            $data['web_image'] = $request->file('web_image')->store('products', 'public');
        }
        if ($request->hasFile('apps_image')) {
            $data['apps_image'] = $request->file('apps_image')->store('products', 'public');
        }

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
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'description' => 'required|string',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'nullable|numeric',
            'web_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'apps_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->only(['name', 'speed', 'description', 'product_category_id', 'price']);

        if ($request->hasFile('web_image')) {
            if ($product->web_image) Storage::disk('public')->delete($product->web_image);
            $data['web_image'] = $request->file('web_image')->store('products', 'public');
        }

        if ($request->hasFile('apps_image')) {
            if ($product->apps_image) Storage::disk('public')->delete($product->apps_image);
            $data['apps_image'] = $request->file('apps_image')->store('products', 'public');
        }

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

    // HIDE/SHOW HARGA PRODUK
    public function togglePrice($id)
    {
        $product = Product::findOrFail($id);
        $product->show_price = !$product->show_price;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Harga produk diperbarui.');
    }
}
