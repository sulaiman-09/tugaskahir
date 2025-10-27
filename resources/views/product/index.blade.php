@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4 text-dark">Product Management</h3>

        {{-- ======================= --}}
        {{-- TABEL 1 : PRODUCT CATEGORY --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-semibold mb-0 text-dark">Product Categories</h6>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('product.category.export', ['product_search' => request('product_search')]) }}"
                            class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                            <i class="fa fa-print me-2"></i> Export CSV
                        </a>

                        <a href="{{ route('product.category.create') }}"
                            class="btn btn-primary btn-sm d-flex align-items-center">
                            <i class="fa fa-plus me-2"></i> Tambah Category Baru
                        </a>

                        <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center">
                            <input type="text" name="category_search" class="form-control form-control-sm"
                                placeholder="Search category name or slug" value="{{ request('category_search') }}">
                            <button type="submit" class="btn btn-primary btn-sm ms-2">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th style="width: 50px;">ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th class="text-start">Short Description</th>
                                <th>Show Price</th>
                                <th class="text-start">Benefits</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td>{{ $cat->id }}</td>
                                    <td class="fw-semibold text-dark text-start ps-3">{{ $cat->name }}</td>
                                    <td>{{ $cat->slug }}</td>
                                    <td class="text-start">{{ $cat->short_description }}</td>
                                    <td>
                                        <form action="{{ route('product.category.update', $cat->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $cat->name }}">
                                            <input type="hidden" name="slug" value="{{ $cat->slug }}">
                                            <input type="hidden" name="short_description"
                                                value="{{ $cat->short_description }}">
                                            <input type="hidden" name="long_description"
                                                value="{{ $cat->long_description }}">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input type="checkbox" class="form-check-input toggle-switch"
                                                    name="show_price" id="show_price_{{ $cat->id }}"
                                                    onchange="this.form.submit()" {{ $cat->show_price ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-start">
                                        @if (!empty($cat->long_description))
                                            <ul class="mb-0 list-unstyled small">
                                                @foreach (preg_split("/\r\n|\n|\r/", trim($cat->long_description)) as $line)
                                                    @if (!empty(trim($line)))
                                                        <li>{!! $line !!}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted fst-italic">No benefits listed.</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('product.category.edit', $cat->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('product.category.destroy', $cat->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin hapus kategori ini?')"
                                                    class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted text-center py-4">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 🔹 Show Per Page Category --}}
                <div class="d-flex justify-content-start mt-3">
                    <form method="GET" action="{{ route('product.index') }}" id="categoryPerPageForm"
                        class="d-flex align-items-center">
                        <label for="category_per_page" class="me-2 text-secondary small mb-0">Show</label>
                        <select name="category_per_page" id="category_per_page" class="form-select form-select-sm w-auto"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'all'] as $size)
                                <option value="{{ $size }}" {{ $categoryPerPage == $size ? 'selected' : '' }}>
                                    {{ is_numeric($size) ? $size : 'All' }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Pertahankan query lain (search, product_per_page, dll) --}}
                        @foreach (request()->except('category_per_page', 'product_per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>

            </div>
        </div>

        {{-- ======================= --}}
        {{-- TABEL 2 : PRODUCT LIST --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h6 class="fw-semibold mb-0 text-dark">Product List</h6>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('product.export', ['product_search' => request('product_search')]) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>

                    <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                        <i class="fa fa-plus me-2"></i> Tambah Product Baru
                    </a>

                    <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="text" name="product_search" class="form-control form-control-sm"
                            placeholder="Search product name, speed, or category"
                            value="{{ request('product_search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Speed</th>
                                <th>Website Image</th>
                                <th>Apps Image</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Created At</th>
                                <th style="width: 110px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $prod)
                                <tr>
                                    <td>{{ $prod->id }}</td>
                                    <td class="text-start ps-3">{{ $prod->name }}</td>
                                    <td>{{ $prod->speed }}</td>
                                    <td>
                                        @if ($prod->web_image)
                                            <img src="{{ asset('storage/' . $prod->web_image) }}" class="img-thumbnail"
                                                width="90">
                                        @endif
                                    </td>
                                    <td>
                                        @if ($prod->apps_image)
                                            <img src="{{ asset('storage/' . $prod->apps_image) }}" class="img-thumbnail"
                                                width="90">
                                        @endif
                                    </td>
                                    <td>{{ $prod->category->name ?? '-' }}</td>
                                    <td>
                                        @if ($prod->show_price)
                                            Rp {{ number_format($prod->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted fst-italic">Hidden</span>
                                        @endif
                                    </td>
                                    <td>{{ $prod->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('product.edit', $prod->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('product.togglePrice', $prod->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if ($prod->show_price)
                                                    <button class="btn btn-secondary btn-sm" title="Hide Price">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-success btn-sm" title="Show Price">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                @endif
                                            </form>

                                            <form action="{{ route('product.destroy', $prod->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin hapus produk ini?')"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted text-center py-4">Belum ada produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 🔹 Show Per Page Product --}}
                <div class="d-flex justify-content-start mt-3">
                    <form method="GET" action="{{ route('product.index') }}" id="productPerPageForm"
                        class="d-flex align-items-center">
                        <label for="product_per_page" class="mb-0 me-2">Show</label>
                        <select name="product_per_page" id="product_per_page" class="form-select form-select-sm w-auto"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'all'] as $size)
                                <option value="{{ $size }}" {{ $productPerPage == $size ? 'selected' : '' }}>
                                    {{ is_numeric($size) ? $size : 'All' }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Pertahankan query lain (search, category_per_page, dll) --}}
                        @foreach (request()->except('category_per_page', 'product_per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
