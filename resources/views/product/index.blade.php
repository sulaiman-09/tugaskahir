@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 product-page">

        {{-- ======================= --}}
        {{-- TABEL 1 : PRODUCT CATEGORY --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">

                <div class="d-flex align-items-center mb-3">

                    {{-- Judul di kiri --}}
                    <h3 class="fw-bold mb-0 text-dark">Product Categories</h3>

                    {{-- Spacer --}}
                    <div class="flex-grow-1"></div>

                    {{-- Toolbar di kanan --}}
                    <div class="d-flex align-items-center gap-2 flex-nowrap" style="white-space: nowrap;">

                        <div class="dropdown toolbar-item">
                            <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center"
                                type="button" id="exportCategoryDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                                <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('product.category.export.excel') }}">Export
                                        Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route('product.category.export.pdf') }}">Export
                                        PDF</a></li>
                            </ul>
                        </div>

                        <a href="{{ route('product.category.create') }}"
                            class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center toolbar-item"
                            style="background-color: #000; border: 1px solid #000; color: #fff; padding: 6px 8px;">
                            <i class="bi bi-bag-plus" style="color: #fff; font-size: 0.875rem;"></i>
                        </a>

                        <button type="button" id="deleteSelectedCategories" class="btn btn-sm toolbar-btn toolbar-item"
                            style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                            <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                        </button>

                        <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center"
                            style="max-width:300px; width:100%;">
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
                                                            style="position: absolute; left: 0; top: 0; color: #0d6efd; font-size: 18px;">&bull;</span>
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
                                            <button type="button" class="btn btn-warning btn-sm edit-category-btn"
                                                data-url="{{ route('product.category.edit', $cat->id) }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
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

            </div>
        </div>

        {{-- ======================= --}}
        {{-- TABEL 2 : PRODUCT LIST --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center">

                {{-- Judul di kiri --}}
                <h3 class="fw-bold mb-0 text-dark">Product List</h3>

                {{-- Spacer agar tombol pindah ke kanan --}}
                <div class="flex-grow-1"></div>

                {{-- Toolbar di kanan --}}
                <div class="d-flex gap-2 align-items-center flex-nowrap" style="white-space: nowrap;">

                    <div class="dropdown toolbar-item">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center"
                            type="button" id="exportProductDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('product.export.excel') }}">Export Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('product.export.pdf') }}">Export PDF</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('product.create') }}"
                        class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center toolbar-item"
                        style="background-color: #000; border: 1px solid #000; color: #fff; padding: 6px 8px;">
                        <i class="bi bi-bag-plus" style="color: #fff; font-size: 0.875rem;"></i>
                    </a>

                    <button type="button" id="deleteSelectedProducts" class="btn btn-sm toolbar-btn toolbar-item"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                    </button>

                    <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center"
                        style="max-width:300px; width:100%">
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
                                            <button type="button" class="btn btn-warning btn-sm edit-product-btn"
                                                data-url="{{ route('product.edit', $prod->id) }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
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

                @php
                    $isProductPaginated = $products instanceof \Illuminate\Contracts\Pagination\Paginator;
                @endphp
                @if ($isProductPaginated)
                    <div class="product-footer border-top bg-light-subtle px-3 py-3 rounded-bottom-3">
                        <div
                            class="pagination-wrapper product-pagination d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <form method="GET" action="{{ route('product.index') }}"
                                    class="d-flex align-items-center gap-2" id="productPageForm">
                                    <label for="product_per_page" class="mb-0 small text-muted">Show</label>
                                    <select name="product_per_page" id="product_per_page"
                                        class="form-select form-select-sm" onchange="this.form.submit()">
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
                                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                                    {{ $products->total() }} results
                                </div>
                            </div>

                            @php
                                $prodCurrent = $products->currentPage();
                                $prodLast = $products->lastPage();
                                $prodStart = max(1, $prodCurrent - 2);
                                $prodEnd = min($prodLast, $prodCurrent + 2);
                            @endphp

                            <nav class="pagination-sm" aria-label="Product pagination">
                                <ul class="pagination mb-0">
                                    <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $products->previousPageUrl() ?? '#' }}"
                                            aria-label="Previous">&lsaquo;</a>
                                    </li>

                                    @if ($prodStart > 1)
                                        <li class="page-item"><a class="page-link" href="{{ $products->url(1) }}">1</a>
                                        </li>
                                        @if ($prodStart > 2)
                                            <li class="page-item disabled"><span class="page-link">…</span></li>
                                        @endif
                                    @endif

                                    @for ($page = $prodStart; $page <= $prodEnd; $page++)
                                        <li class="page-item {{ $page === $prodCurrent ? 'active' : '' }}">
                                            @if ($page === $prodCurrent)
                                                <span class="page-link">{{ $page }}</span>
                                            @else
                                                <a class="page-link"
                                                    href="{{ $products->url($page) }}">{{ $page }}</a>
                                            @endif
                                        </li>
                                    @endfor

                                    @if ($prodEnd < $prodLast)
                                        @if ($prodEnd < $prodLast - 1)
                                            <li class="page-item disabled"><span class="page-link">…</span></li>
                                        @endif
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $products->url($prodLast) }}">{{ $prodLast }}</a></li>
                                    @endif

                                    <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $products->nextPageUrl() ?? '#' }}"
                                            aria-label="Next">&rsaquo;</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Modal edit global --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editModalBody">
                    <div class="text-center py-4">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal preview image (single instance) --}}
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" class="img-fluid" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modalEl = document.getElementById('editModal');
                const modalBody = document.getElementById('editModalBody');
                const modalTitle = document.getElementById('editModalTitle');
                const editModal = new bootstrap.Modal(modalEl);

                function wireModalForm(container) {
                    const form = container.querySelector('form');
                    if (!form) return;

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const submitBtn = form.querySelector('[type="submit"]');
                        if (submitBtn) submitBtn.disabled = true;

                        const errorBox = form.querySelector('[data-error-box]');
                        if (errorBox) {
                            errorBox.classList.add('d-none');
                            errorBox.innerHTML = '';
                        }

                        fetch(form.action, {
                                method: form.method || 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: new FormData(form)
                            })
                            .then(async res => {
                                if (submitBtn) submitBtn.disabled = false;

                                if (res.ok) {
                                    editModal.hide();
                                    window.location.reload();
                                    return;
                                }

                                if (res.status === 422) {
                                    const data = await res.json();
                                    const messages = Object.values(data.errors || {}).flat();
                                    if (errorBox) {
                                        errorBox.classList.remove('d-none');
                                        errorBox.innerHTML = messages.map(m => `<div>${m}</div>`).join(
                                            '');
                                    }
                                    return;
                                }

                                alert('Gagal menyimpan data. Silakan coba lagi.');
                            })
                            .catch(() => {
                                if (submitBtn) submitBtn.disabled = false;
                                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                            });
                    });
                }

                function loadEditForm(url, titleText) {
                    modalTitle.textContent = titleText;
                    modalBody.innerHTML = '<div class="text-center py-4">Loading...</div>';
                    editModal.show();

                    fetch(url + (url.includes('?') ? '&' : '?') + 'modal=1', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.text())
                        .then(html => {
                            modalBody.innerHTML = html;
                            wireModalForm(modalBody);
                        })
                        .catch(() => {
                            modalBody.innerHTML = '<div class="text-danger">Gagal memuat form.</div>';
                        });
                }

                document.querySelectorAll('.edit-category-btn').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.preventDefault();
                        loadEditForm(btn.dataset.url, 'Edit Category');
                    });
                });

                document.querySelectorAll('.edit-product-btn').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.preventDefault();
                        loadEditForm(btn.dataset.url, 'Edit Product');
                    });
                });
            });
        </script>

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
