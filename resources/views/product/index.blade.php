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

                <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
                    <h6 class="fw-semibold mb-0 text-dark">Product Categories</h6>

                    <div class="d-flex gap-2 align-items-center toolbar-scroll">
                        <div class="dropdown">
                            <button
                                class="btn btn-outline-secondary btn-sm toolbar-btn toolbar-btn-ghost d-flex align-items-center"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-print me-2"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('product.category.export.excel') }}">Export
                                        Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route('product.category.export.pdf') }}">Export
                                        PDF</a></li>
                            </ul>
                        </div>

                        <a href="{{ route('product.category.create') }}" class="btn btn-sm toolbar-btn toolbar-btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add New Category
                        </a>

                        <button type="button" id="deleteSelectedCategories"
                            class="btn btn-sm toolbar-btn toolbar-btn-danger">
                            <i class="fa fa-trash me-1"></i> Delete Selected
                        </button>

                        <form action="{{ route('product.index') }}" method="GET" class="ms-auto d-flex align-items-center"
                            style="max-width:360px; width:100%">
                            <div class="input-group input-group-sm w-100">
                                <input type="text" name="category_search" class="form-control form-control-sm"
                                    placeholder="Search category name or slug" value="{{ request('category_search') }}">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
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
                                    <td colspan="8" class="text-muted text-center py-4">No category data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-3 flex-wrap">

                    {{-- Left: Show per page + showing text --}}
                    <div class="d-flex align-items-center flex-wrap gap-3">

                        {{-- Show Per Page Category --}}
                        <form method="GET" action="{{ route('product.index') }}" class="d-flex align-items-center gap-2">
                            <label for="category_per_page" class="mb-0 small text-muted">Show</label>

                            <select name="category_per_page" id="category_per_page" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100, 'All'] as $size)
                                    <option value="{{ $size }}"
                                        {{ strtolower(request('category_per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Keep other query params --}}
                            @foreach (request()->except('category_per_page', 'product_per_page', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        </form>

                        {{-- Showing text --}}
                        <div class="showing-text small text-muted">
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
                            of {{ $categories->total() }} results
                        </div>
                    </div>

                    {{-- Right: Pagination --}}
                    <div class="right-pagination pagination-sm">
                        {{ $categories->appends(request()->query())->onEachSide(0)->links() }}
                    </div>
                </div>

            </div>
        </div>

        {{-- ======================= --}}
        {{-- TABEL 2 : PRODUCT LIST --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                <h6 class="fw-semibold mb-0 text-dark">Product List</h6>

                <div class="d-flex gap-2 align-items-center toolbar-scroll">
                    <div class="dropdown">
                        <button
                            class="btn btn-outline-secondary btn-sm toolbar-btn toolbar-btn-ghost d-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-print me-2"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('product.export.excel') }}">Export Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('product.export.pdf') }}">Export PDF</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('product.create') }}" class="btn btn-sm toolbar-btn toolbar-btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add New Product
                    </a>

                    <button type="button" id="deleteSelectedProducts" class="btn btn-sm toolbar-btn toolbar-btn-danger">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>

                    <form action="{{ route('product.index') }}" method="GET" class="ms-auto d-flex align-items-center"
                        style="max-width:360px; width:100%">
                        <div class="input-group input-group-sm w-100">
                            <input type="text" name="product_search" class="form-control form-control-sm"
                                placeholder="Search product name, speed, or category"
                                value="{{ request('product_search') }}">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
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
                                    <td colspan="10" class="text-muted text-center py-4">No category data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div
                    class="pagination-wrapper product-pagination d-flex justify-content-between align-items-center mt-3 flex-wrap">

                    {{-- Left: Show Per Page + Showing --}}
                    <div class="d-flex align-items-center flex-wrap gap-3">

                        <form method="GET" action="{{ route('product.index') }}"
                            class="d-flex align-items-center gap-2" id="productPageForm">

                            <label for="product_per_page" class="mb-0 small text-muted">Show</label>

                            <select name="product_per_page" id="product_per_page" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100, 'All'] as $size)
                                    <option value="{{ $size }}"
                                        {{ strtolower(request('product_per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>

                            @foreach (request()->except('category_per_page', 'product_per_page', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        </form>

                        <div class="showing-text small text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                            of {{ $products->total() }} results
                        </div>
                    </div>

                    {{-- Right pagination --}}
                    <div class="right-pagination pagination-sm">
                        {{ $products->appends(request()->query())->onEachSide(0)->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Wrapper pagination */
            .product-pagination {
                gap: 10px !important;
            }

            /* Styling dropdown show per page */
            .product-pagination #product_per_page {
                min-width: 80px;
                border-radius: 8px;
                padding: 5px 10px;
                background-color: #fff;
            }

            .product-pagination .showing-text {
                white-space: nowrap;
                font-size: 0.85rem;
            }

            /* ======== FIX PANAH PREVIOUS/NEXT ======== */
            /* Kecilkan tombol */
            .product-pagination .pagination .page-link {
                padding: 4px 8px !important;
                font-size: 10px !important;
                line-height: 1 !important;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 2px;
            }

            /* Kecilkan ikon SVG bawaan Laravel pagination */
            .product-pagination .pagination .page-link svg {
                width: 10px !important;
                height: 10px !important;
            }

            /* Untuk tombol Prev / Next (karena isinya SVG saja) */
            .product-pagination .pagination .page-item .page-link[rel="prev"],
            .product-pagination .pagination .page-item .page-link[rel="next"] {
                padding: 4px 8px !important;
            }

            /* Toolbar button styles (re-used for consistency with Customer/SudirmanPark) */
            .toolbar-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.35rem 0.7rem;
                border-radius: 10px;
                font-size: 0.88rem;
                color: #334155;
                /* slate-700 */
                background: transparent;
                border: 1px solid rgba(15, 23, 42, 0.04);
                transition: all 0.12s ease-in-out;
            }

            .toolbar-btn:hover {
                background: #ffffff;
                box-shadow: 0 4px 12px rgba(2, 6, 23, 0.06);
                transform: translateY(-1px);
                color: #0f172a;
            }

            .toolbar-btn:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
            }

            .toolbar-btn-primary {
                background: linear-gradient(180deg, #1f2937, #111827);
                color: #ffffff !important;
                border-color: rgba(0, 0, 0, 0.08);
                box-shadow: 0 8px 20px rgba(17, 24, 39, 0.12);
            }

            .toolbar-btn-primary:hover {
                transform: translateY(-1px);
                filter: brightness(1.03);
                color: #ffffff !important;
                background: linear-gradient(180deg, #111827, #0b1220);
                box-shadow: 0 10px 24px rgba(17, 24, 39, 0.16);
            }

            .toolbar-btn-ghost {
                background: transparent;
                color: #374151;
            }

            .toolbar-btn-danger {
                background: transparent;
                color: #b91c1c;
                border-color: rgba(185, 28, 28, 0.08);
            }

            .toolbar-btn-danger:hover {
                background: #b91c1c;
                color: #fff !important;
                box-shadow: 0 6px 18px rgba(185, 28, 28, 0.12);
            }

            .toolbar-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                white-space: nowrap;
                gap: 0.5rem;
                padding-bottom: 4px;
            }

            .toolbar-scroll::-webkit-scrollbar {
                height: 6px;
            }

            .toolbar-scroll::-webkit-scrollbar-thumb {
                background: rgba(15, 23, 42, 0.06);
                border-radius: 6px;
            }

            /* FIX Dropdown ketutup tabel */
            .toolbar-scroll {
                position: relative;
                overflow: visible !important;
                /* izinkan dropdown keluar */
            }

            .toolbar-scroll .dropdown-menu {
                z-index: 9999 !important;
                position: absolute !important;
            }
        </style>
    @endpush

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
