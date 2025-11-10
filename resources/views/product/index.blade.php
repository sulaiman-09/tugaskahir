@php use Illuminate\Support\Str; @endphp
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
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="fa fa-print me-2"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('product.category.export.excel') }}">Export
                                        Excel</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('product.category.export.pdf') }}">Export
                                        PDF</a></li>
                            </ul>
                        </div>


                        <a href="{{ route('product.category.create') }}"
                            class="btn btn-primary btn-sm d-flex align-items-center">
                            <i class="fa fa-plus me-2"></i> Tambah Category Baru
                        </a>

                        <button type="button" id="deleteSelectedCategories" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash me-1"></i> Delete Selected
                        </button>

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
                                <th><input type="checkbox" id="selectAllCategories"></th>
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
                                    <td><input type="checkbox" class="select-category" value="{{ $cat->id }}"></td>
                                    <td>{{ $cat->id }}</td>
                                    <td class="fw-semibold text-dark text-start ps-3">{{ $cat->name }}</td>
                                    <td>{{ $cat->slug }}</td>
                                    <td class="text-start">{{ $cat->short_description }}</td>
                                    <td>
                                        <form action="{{ route('product.category.togglePrice', $cat->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input type="checkbox" class="form-check-input" name="show_price"
                                                    id="show_price_{{ $cat->id }}" onchange="this.form.submit()"
                                                    {{ $cat->show_price ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-start">
                                        @if ($cat->benefits->count() > 0)
                                            <ul class="mb-0 list-unstyled" style="padding-left: 25px; text-align: justify;">
                                                @foreach ($cat->benefits as $benefit)
                                                    <li style="margin-bottom: 5px; position: relative; padding-left: 18px;">
                                                        <span
                                                            style="position: absolute; left: 0; top: 0; color: #0d6efd; font-size: 18px;">•</span>
                                                        {{ $benefit->description }}
                                                    </li>
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
                                    <td colspan="8" class="text-muted text-center py-4">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Show Per Page Category --}}
                <div class="d-flex justify-content-start mt-3">
                    <form method="GET" action="{{ route('product.index') }}" class="d-flex align-items-center gap-2">
                        <label for="category_per_page" class="mb-0">Show</label>
                        <select name="category_per_page" id="category_per_page" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'All'] as $size)
                                <option value="{{ $size }}"
                                    {{ strtolower(request('category_per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Keep other query params (except product_per_page & page) --}}
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
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                <h6 class="fw-semibold mb-0 text-dark">Product List</h6>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <i class="fa fa-print me-2"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('product.export.excel') }}">Export Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('product.export.pdf') }}">Export PDF</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                        <i class="fa fa-plus me-2"></i> Tambah Product Baru
                    </a>

                    <button type="button" id="deleteSelectedProducts" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>

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
                                <th><input type="checkbox" id="selectAllProducts"></th>
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
                                    <td><input type="checkbox" class="select-product" value="{{ $prod->id }}"></td>
                                    <td>{{ $prod->id }}</td>
                                    <td class="text-start ps-3">{{ $prod->name }}</td>
                                    <td>{{ $prod->speed }}</td>
                                    <td>
                                        @if ($prod->web_image)
                                            <button type="button" class="btn btn-sm btn-outline-secondary preview-btn"
                                                data-preview-url="{{ asset('storage/' . $prod->web_image) }}">
                                                View
                                            </button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($prod->path_apps)
                                            <button type="button" class="btn btn-sm btn-outline-secondary preview-btn"
                                                data-preview-url="{{ asset('storage/' . $prod->path_apps) }}">
                                                View
                                            </button>
                                        @else
                                            <span class="text-muted">No File</span>
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
                                                <button
                                                    class="btn btn-sm {{ $prod->show_price ? 'btn-secondary' : 'btn-success' }}"
                                                    title="{{ $prod->show_price ? 'Hide Price' : 'Show Price' }}">
                                                    <i class="bi {{ $prod->show_price ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                </button>
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
                                            <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Preview Image</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img id="previewImage" src="" class="img-fluid"
                                                                alt="Preview">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-muted text-center py-4">Belum ada produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Show Per Page Product --}}
                <div class="d-flex justify-content-start mt-3">
                    <form method="GET" action="{{ route('product.index') }}" class="d-flex align-items-center gap-2">
                        <label for="product_per_page" class="mb-0">Show</label>
                        <select name="product_per_page" id="product_per_page" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'All'] as $size)
                                <option value="{{ $size }}"
                                    {{ strtolower(request('product_per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Keep other query params (except category_per_page & page) --}}
                        @foreach (request()->except('category_per_page', 'product_per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ================= Bulk Delete Category =================
                const selectAllCategories = document.getElementById('selectAllCategories');
                const categoryCheckboxes = document.querySelectorAll('.select-category');
                selectAllCategories.addEventListener('change', function() {
                    categoryCheckboxes.forEach(cb => cb.checked = selectAllCategories.checked);
                });

                document.getElementById('deleteSelectedCategories').addEventListener('click', function() {
                    const selectedIds = Array.from(categoryCheckboxes).filter(cb => cb.checked).map(cb => cb
                        .value);
                    if (selectedIds.length === 0) {
                        alert('No categories selected.');
                        return;
                    }
                    if (!confirm(
                            `Are you sure you want to delete ${selectedIds.length} categories? This cannot be undone.`
                        )) return;

                    fetch("{{ route('product.category.bulkDelete') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else alert(data.message);
                        })
                        .catch(() => alert('Error, please try again.'));
                });

                // ================= Bulk Delete Product =================
                const selectAllProducts = document.getElementById('selectAllProducts');
                const productCheckboxes = document.querySelectorAll('.select-product');
                selectAllProducts.addEventListener('change', function() {
                    productCheckboxes.forEach(cb => cb.checked = selectAllProducts.checked);
                });

                document.getElementById('deleteSelectedProducts').addEventListener('click', function() {
                    const selectedIds = Array.from(productCheckboxes).filter(cb => cb.checked).map(cb => cb
                        .value);
                    if (selectedIds.length === 0) {
                        alert('No products selected.');
                        return;
                    }
                    if (!confirm(
                            `Are you sure you want to delete ${selectedIds.length} products? This cannot be undone.`
                        )) return;

                    fetch("{{ route('product.bulkDelete') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else alert(data.message);
                        })
                        .catch(() => alert('Error, please try again.'));
                });
            });
        </script>

        <script>
            document.querySelectorAll('.preview-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const url = this.dataset.previewUrl;
                    const img = document.getElementById('previewImage');
                    img.src = url;
                    new bootstrap.Modal(document.getElementById('previewModal')).show();
                });
            });
        </script>
    @endpush
@endsection
