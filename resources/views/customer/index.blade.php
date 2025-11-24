@extends('layouts.app')

@section('title', 'Data Customers')

@section('content')
    @php
        $filters = [
            'all' => 'All Records',
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'last_week' => 'Last Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'last_7_days' => 'Last 7 Days',
            'last_30_days' => 'Last 30 Days',
        ];
        $currentFilter = request('filter', 'all');
    @endphp

    <div class="container py-4">

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body py-3">
                <div class="d-flex gap-2 align-items-center filter-scroll">
                    @foreach ($filters as $key => $label)
                        @php $isActive = $currentFilter == $key; @endphp
                        <a href="{{ route('customer.index', ['filter' => $key]) }}"
                            class="filter-btn btn-sm {{ $isActive ? 'active' : '' }}"
                            aria-current="{{ $isActive ? 'page' : 'false' }}"
                            style="color: #000; border: 1px solid #000; {{ $isActive ? 'background-color: #000; color: #fff;' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    <button type="button" class="filter-btn btn-sm ms-1" data-bs-toggle="collapse"
                        data-bs-target="#customRange" style="color: #000; border: 1px solid #000;">
                        Custom Range
                    </button>
                </div>

                <div id="customRange" class="collapse mt-3">
                    <form method="GET" action="{{ route('customer.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small">From</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">To</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" name="filter" value="custom"
                                class="btn btn-primary w-100 btn-sm">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <!-- Judul kiri -->
                <h3 class="fw-bold mb-0">Data Customer Leads</h3>

                <!-- Toolbar kanan -->
                <div class="d-flex align-items-center gap-2 justify-content-end flex-grow-1">
                    <!-- Export Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center"
                            type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('customer.export.excel') }}">Export Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.export.pdf') }}">Export PDF</a></li>
                        </ul>
                    </div>

                    <!-- Add Customer -->
                    <a href="{{ route('customer.create') }}"
                        class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center"
                        style="background-color: #000; border: 1px solid #000; color: #fff; padding: 6px 8px; width: 36px; height: 36px;">
                        <i class="bi bi-person-plus" style="color: #fff; font-size: 1rem;"></i>
                    </a>

                    <!-- Delete Selected -->
                    <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                    </button>

                    <!-- Toggle Coordinates -->
                    <button id="toggle-coordinates" type="button" class="btn btn-sm toolbar-btn toolbar-btn-ghost"
                        style="background-color: white; border: 1px solid #000; color: #000;">
                        Show Coordinates
                    </button>

                    <!-- Search Form -->
                    <form action="{{ route('customer.index') }}" method="GET"
                        class="d-flex align-items-center flex-shrink-0" style="min-width: 260px; max-width: 400px;">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search name, phone, email or product" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless"
                        style="white-space: nowrap; min-width: 100%;">

                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th><input type="checkbox" id="selectAll"></th>
                                <th style="width: 40px;">No</th>
                                <th>Customer Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Coverage</th>
                                <th>Product</th>
                                <th>Division</th>
                                <th>Created At</th>
                                <th>Details</th>
                                <th style="width: 110px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($customer_leads as $index => $customer_lead)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="customer-checkbox" value="{{ $customer_lead->id }}">
                                    </td>

                                    <td>{{ $customer_leads->firstItem() + $index }}</td>

                                    <td class="text-start ps-3">{{ $customer_lead->customer_name }}</td>

                                    <td>{{ $customer_lead->customer_phone }}</td>

                                    <td>{{ $customer_lead->email }}</td>

                                    <td class="text-start">{{ $customer_lead->customer_address }}</td>

                                    <td>{{ $customer_lead->latitude }}</td>

                                    <td>{{ $customer_lead->longitude }}</td>

                                    <td>
                                        <select class="form-select form-select-sm coverage-dropdown"
                                            data-id="{{ $customer_lead->id }}">
                                            <option value="">Select Coverage</option>
                                            <option value="Cover"
                                                {{ $customer_lead->coverage == 'Cover' ? 'selected' : '' }}>Cover</option>
                                            <option value="Uncover"
                                                {{ $customer_lead->coverage == 'Uncover' ? 'selected' : '' }}>Uncover
                                            </option>
                                        </select>
                                    </td>

                                    <td class="text-start">
                                        {{ $customer_lead->product?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $customer_lead->productCategory?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $customer_lead->created_at ? $customer_lead->created_at->format('d M Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info btn-detail-map"
                                            data-lat="{{ $customer_lead->latitude }}"
                                            data-lng="{{ $customer_lead->longitude }}"
                                            data-address="{{ e($customer_lead->customer_address) }}">
                                            Maps
                                        </button>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-warning btn-sm btn-edit-customer"
                                                title="Edit" data-id="{{ $customer_lead->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <form action="{{ route('customer.destroy', $customer_lead->id) }}"
                                                method="POST" class="delete-form"
                                                data-name="{{ $customer_lead->customer_name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="14" class="text-muted text-center py-4">
                                        No customer data available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-3 flex-wrap">
            <!-- Left: Show Per Page + Showing Text -->
            <div class="left-info d-flex align-items-center flex-wrap gap-2">

                <form method="GET" id="perPageForm" class="d-flex align-items-center gap-2">
                    <label for="per_page" class="m-0 small text-muted">Show</label>

                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="form-select form-select-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Keep other filters --}}
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                <div class="showing-text small text-muted">
                    Showing {{ $customer_leads->firstItem() }} to {{ $customer_leads->lastItem() }} of
                    {{ $customer_leads->total() }} results
                </div>
            </div>

            <!-- Right: Pagination -->
            <div class="right-pagination">
                @if ($customer_leads->hasPages())
                    {{-- Variabel untuk kemudahan --}}
                    @php
                        $current = $customer_leads->currentPage();
                        $last = $customer_leads->lastPage();

                        // Logika sliding window: 2 halaman sebelum dan 2 sesudah halaman aktif
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    <div class="flex justify-end mt-4">
                        <nav class="inline-flex items-center space-x-1 text-sm">
                            {{-- Tombol Previous --}}
                            @if ($customer_leads->onFirstPage())
                                <span class="px-3 py-1 border rounded-md opacity-40 cursor-not-allowed">‹</span>
                            @else
                                <a href="{{ $customer_leads->previousPageUrl() }}"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-100">‹</a>
                            @endif

                            {{-- Halaman Pertama dan Ellipsis (jika perlu) --}}
                            @if ($start > 1)
                                <a href="{{ $customer_leads->url(1) }}"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-100">1</a>
                                @if ($start > 2)
                                    <span class="px-3 py-1 border rounded-md opacity-60">…</span>
                                @endif
                            @endif

                            {{-- Loop Angka Halaman (Sliding Window) --}}
                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $current)
                                    <span
                                        class="px-3 py-1 border rounded-md bg-blue-600 text-white border-blue-600">{{ $page }}</span>
                                @else
                                    <a href="{{ $customer_leads->url($page) }}"
                                        class="px-3 py-1 border rounded-md hover:bg-gray-100">{{ $page }}</a>
                                @endif
                            @endfor

                            {{-- Ellipsis dan Halaman Terakhir (jika perlu) --}}
                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span class="px-3 py-1 border rounded-md opacity-60">…</span>
                                @endif
                                <a href="{{ $customer_leads->url($last) }}"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-100">{{ $last }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($customer_leads->hasMorePages())
                                <a href="{{ $customer_leads->nextPageUrl() }}"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-100">›</a>
                            @else
                                <span class="px-3 py-1 border rounded-md opacity-40 cursor-not-allowed">›</span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>

        </div>


        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Konfirmasi hapus
                    document.querySelectorAll('.delete-form').forEach(form => {
                        form.addEventListener('submit', e => {
                            e.preventDefault();
                            const name = form.dataset.name || 'record ini';
                            if (confirm(
                                    `Yakin ingin menghapus ${name}? Aksi ini tidak dapat dibatalkan.`)) {
                                form.submit();
                            }
                        });
                    });
                });
            </script>
        @endpush
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggleButton = document.getElementById('toggle-coordinates');
                    const latitudeCols = document.querySelectorAll('td:nth-child(7), th:nth-child(7)');
                    const longitudeCols = document.querySelectorAll('td:nth-child(8), th:nth-child(8)');
                    // default: coordinates hidden
                    let visible = false;
                    latitudeCols.forEach(col => col.style.display = 'none');
                    longitudeCols.forEach(col => col.style.display = 'none');
                    toggleButton.textContent = 'Show Coordinats';

                    toggleButton.addEventListener('click', function() {
                        visible = !visible;
                        latitudeCols.forEach(col => col.style.display = visible ? '' : 'none');
                        longitudeCols.forEach(col => col.style.display = visible ? '' : 'none');
                        toggleButton.textContent = visible ? 'Hide Coordinats' : 'Show Coordinats';
                    });
                });
            </script>
        @endpush

    @endsection

    @push('styles')
        <style>
            /* Dropdown Show Per Page */
            #per_page {
                min-width: 80px;
                border-radius: 8px;
                padding: 5px 10px;
                background-color: #fff;
                position: relative;
                z-index: 10;
            }

            #perPageForm {
                display: flex;
                align-items: center;
                gap: 8px;
                margin: 0;
            }

            /* Wrapper */
            .pagination-wrapper {
                gap: 10px !important;
            }

            /* Showing text kecil dan rapi */
            .showing-text {
                white-space: nowrap;
                font-size: 0.85rem;
            }

            /* Kecilkan pagination */
            .pagination-sm .page-link {
                font-size: 0.78rem;
                padding: 4px 10px;
                line-height: 1;
                border-radius: 6px;
            }

            .pagination-sm .page-item {
                margin: 0 2px;
            }

            /* Kecilkan icon SVG prev/next */
            .pagination-sm svg {
                width: 12px !important;
                height: 12px !important;
            }

            /* Perbaiki posisi biar tidak turun-naik */
            .right-pagination {
                display: flex;
                align-items: center;
            }

            /* Responsif */
            @media (max-width: 480px) {
                .pagination-sm .page-link {
                    padding: 3px 6px;
                }
            }

            /* Filter button styles - clean, interactive, no color noise */
            .filter-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.28rem 0.65rem;
                border-radius: 8px;
                font-size: 0.86rem;
                color: #6b7280;
                /* slate-500 */
                background: transparent;
                border: 1px solid transparent;
                text-decoration: none;
                transition: all 0.12s ease-in-out;
            }

            /* Keep filter buttons on a single horizontal line and allow scrolling */
            .filter-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                white-space: nowrap;
                gap: 0.5rem;
                padding-bottom: 4px;
            }

            .filter-scroll::-webkit-scrollbar {
                height: 6px;
            }

            .filter-scroll::-webkit-scrollbar-thumb {
                background: rgba(15, 23, 42, 0.06);
                border-radius: 6px;
            }

            .filter-btn {
                white-space: nowrap;
            }

            .filter-btn:hover {
                color: #374151;
                /* slate-700 */
                background: #ffffff;
                box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
                transform: translateY(-1px);
            }

            .filter-btn:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            }

            .filter-btn.active {
                background: #ffffff;
                color: #0f172a;
                /* slate-900 */
                box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
                border-color: rgba(15, 23, 42, 0.04);
            }

            /* Toolbar buttons (Export, Add, Delete, Show Coordinates) */
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

            /* primary (Add New) - subtle, elegant */
            .toolbar-btn-primary {
                background: linear-gradient(180deg, #1f2937, #111827);
                color: #ffffff !important;
                border-color: rgba(0, 0, 0, 0.08);
                box-shadow: 0 8px 20px rgba(17, 24, 39, 0.12);
            }

            .toolbar-btn-primary:hover {
                transform: translateY(-1px);
                filter: brightness(1.03);
                background: linear-gradient(180deg, #111827, #0b1220);
                color: #ffffff !important;
                box-shadow: 0 10px 24px rgba(17, 24, 39, 0.16);
            }

            /* ghost / neutral (export, show coords) */
            .toolbar-btn-ghost {
                background: transparent;
                color: #374151;
            }

            /* danger (delete) - muted red */
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
        </style>

        </style>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush
    <!-- Edit Customer Modal -->
    <div class="modal fade" id="customerEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="customerEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Nama Pelanggan</label>
                                    <input type="text" name="customer_name" id="cust-name"
                                        class="form-control rounded-3" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Nomor Telepon</label>
                                    <input type="text" name="customer_phone" id="cust-phone"
                                        class="form-control rounded-3" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small">Alamat Lengkap</label>
                                    <textarea name="address" id="cust-address" class="form-control rounded-3" rows="2" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" name="email" id="cust-email"
                                        class="form-control rounded-3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Kode Referral</label>
                                    <input type="text" name="referral_code" id="cust-referral"
                                        class="form-control rounded-3">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small">Customer Address</label>
                                    <textarea name="customer_address" id="cust-customer_address" class="form-control rounded-3" rows="2"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Latitude</label>
                                    <input type="text" name="latitude" id="cust-latitude"
                                        class="form-control rounded-3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Longitude</label>
                                    <input type="text" name="longitude" id="cust-longitude"
                                        class="form-control rounded-3">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Provinsi</label>
                                    <select id="cust-province" name="province" class="form-select rounded-3"
                                        required></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Kota/Kabupaten</label>
                                    <select id="cust-city" name="city" class="form-select rounded-3"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Kecamatan</label>
                                    <select id="cust-district" name="district"
                                        class="form-select rounded-3"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Kelurahan/Desa</label>
                                    <select id="cust-village" name="village" class="form-select rounded-3"></select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Division</label>
                                    <select name="division" id="cust-division" class="form-select rounded-3">
                                        <option value="">Pilih Division</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales Retail">Sales Retail</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Kategori Produk</label>
                                    <select name="product_category" id="cust-product_category"
                                        class="form-select rounded-3">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Produk</label>
                                    <select name="product" id="cust-product" class="form-select rounded-3">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Coverage</label>
                                    <select name="coverage" id="cust-coverage" class="form-select rounded-3">
                                        <option value="">Pilih Coverage</option>
                                        <option value="Cover">Cover</option>
                                        <option value="Uncover">Uncover</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function initCustomerEdit() {
                    if (typeof bootstrap === 'undefined' || !document.getElementById('customerEditModal')) {
                        return setTimeout(initCustomerEdit, 100);
                    }

                    const modalEl = document.getElementById('customerEditModal');
                    const modal = new bootstrap.Modal(modalEl);
                    const form = document.getElementById('customerEditForm');
                    let currentId = null;

                    function populateRegionSelects(customer) {
                        const provinceSelect = document.getElementById('cust-province');
                        const citySelect = document.getElementById('cust-city');
                        const districtSelect = document.getElementById('cust-district');
                        const villageSelect = document.getElementById('cust-village');

                        provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

                        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                            .then(res => res.json())
                            .then(provinces => {
                                provinces.forEach(p => {
                                    const opt = document.createElement('option');
                                    opt.value = p.name;
                                    opt.textContent = p.name;
                                    opt.dataset.id = p.id;
                                    if (p.name === (customer.province || '')) opt.selected = true;
                                    provinceSelect.appendChild(opt);
                                });

                                if (customer.province) {
                                    const sel = Array.from(provinceSelect.options).find(o => o.value === customer
                                        .province);
                                    if (sel && sel.dataset.id) {
                                        loadCities(sel.dataset.id, customer.city);
                                    }
                                }
                            });

                        function loadCities(provinceId, selectedCity) {
                            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                                .then(res => res.json())
                                .then(cities => {
                                    cities.forEach(c => {
                                        const opt = document.createElement('option');
                                        opt.value = c.name;
                                        opt.textContent = c.name;
                                        opt.dataset.id = c.id;
                                        if (c.name === (selectedCity || '')) opt.selected = true;
                                        citySelect.appendChild(opt);
                                    });
                                    if (selectedCity) {
                                        const selCity = Array.from(citySelect.options).find(o => o.value ===
                                            selectedCity);
                                        if (selCity && selCity.dataset.id) loadDistricts(selCity.dataset.id,
                                            customer.district);
                                    }
                                });
                        }

                        function loadDistricts(cityId, selectedDistrict) {
                            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`)
                                .then(res => res.json())
                                .then(districts => {
                                    districts.forEach(d => {
                                        const opt = document.createElement('option');
                                        opt.value = d.name;
                                        opt.textContent = d.name;
                                        opt.dataset.id = d.id;
                                        if (d.name === (selectedDistrict || '')) opt.selected = true;
                                        districtSelect.appendChild(opt);
                                    });
                                    if (selectedDistrict) {
                                        const selDist = Array.from(districtSelect.options).find(o => o.value ===
                                            selectedDistrict);
                                        if (selDist && selDist.dataset.id) loadVillages(selDist.dataset.id, customer
                                            .village);
                                    }
                                });
                        }

                        function loadVillages(districtId, selectedVillage) {
                            villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
                                .then(res => res.json())
                                .then(villages => {
                                    villages.forEach(v => {
                                        const opt = document.createElement('option');
                                        opt.value = v.name;
                                        opt.textContent = v.name;
                                        if (v.name === (selectedVillage || '')) opt.selected = true;
                                        villageSelect.appendChild(opt);
                                    });
                                });
                        }

                        provinceSelect.addEventListener('change', function() {
                            const id = this.selectedOptions[0]?.dataset.id;
                            if (!id) return;
                            loadCities(id);
                        });

                        document.getElementById('cust-city').addEventListener('change', function() {
                            const id = this.selectedOptions[0]?.dataset.id;
                            if (!id) return;
                            loadDistricts(id);
                        });

                        document.getElementById('cust-district').addEventListener('change', function() {
                            const id = this.selectedOptions[0]?.dataset.id;
                            if (!id) return;
                            loadVillages(id);
                        });
                    }

                    document.querySelectorAll('.btn-edit-customer').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.dataset.id;
                            currentId = id;
                            fetch(`/customer/${id}/edit`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(r => r.json())
                                .then(payload => {
                                    const customer = payload.customer || payload;
                                    document.getElementById('cust-name').value = customer
                                        .customer_name || '';
                                    document.getElementById('cust-phone').value = customer
                                        .customer_phone || '';
                                    document.getElementById('cust-address').value = customer
                                        .address || '';
                                    document.getElementById('cust-email').value = customer.email ||
                                        '';
                                    document.getElementById('cust-referral').value = customer
                                        .referral_code || '';
                                    document.getElementById('cust-customer_address').value =
                                        customer.customer_address || '';
                                    document.getElementById('cust-latitude').value = customer
                                        .latitude || '';
                                    document.getElementById('cust-longitude').value = customer
                                        .longitude || '';
                                    document.getElementById('cust-division').value = customer
                                        .division || '';
                                    document.getElementById('cust-product_category').value =
                                        customer.product_category || '';
                                    document.getElementById('cust-product').value = customer
                                        .product_id || '';
                                    document.getElementById('cust-coverage').value = customer
                                        .coverage || '';

                                    populateRegionSelects(customer);

                                    modal.show();
                                }).catch(err => {
                                    console.error(err);
                                    Swal.fire('Error', 'Gagal mengambil data customer', 'error');
                                });
                        });
                    });

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (!currentId) return Swal.fire('Error', 'ID tidak tersedia', 'error');

                        const fd = new FormData(form);
                        fd.append('_method', 'PUT');

                        fetch(`/customer/${currentId}`, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: fd
                            })
                            .then(async r => {
                                const json = await r.json().catch(() => ({
                                    success: false,
                                    message: 'Invalid JSON'
                                }));
                                if (r.ok && json.success) {
                                    modal.hide();
                                    Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: json.message || 'Perubahan disimpan'
                                        })
                                        .then(() => window.location.reload());
                                } else {
                                    const msg = json.message || 'Gagal menyimpan. Cek input.';
                                    Swal.fire('Gagal', msg, 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                            });
                    });
                }

                initCustomerEdit();
            });
        </script>
    @endpush

    <!-- Map Modal -->
    <div class="modal fade" id="mapDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Alamat & Peta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mapContainer" style="height:450px; width:100%;"></div>
                    <div id="mapAddress" class="mt-2 small text-muted"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function initMapHandlers() {
                    if (typeof bootstrap === 'undefined' || !document.getElementById('mapDetailModal')) {
                        return setTimeout(initMapHandlers, 100);
                    }
                    let map = null;
                    const mapModalEl = document.getElementById('mapDetailModal');
                    const mapModal = new bootstrap.Modal(mapModalEl);

                    function openMap(lat, lng, address) {
                        const container = document.getElementById('mapContainer');
                        // clear previous map if any
                        if (map) {
                            try {
                                map.remove();
                            } catch (e) {
                                /* ignore */
                            }
                            map = null;
                        }

                        if (!lat || !lng) {
                            document.getElementById('mapAddress').textContent =
                                'Koordinat tidak tersedia untuk alamat ini.';
                            container.innerHTML =
                                '<div class="text-center text-muted py-5">Koordinat tidak tersedia.</div>';
                            mapModal.show();
                            return;
                        }

                        map = L.map('mapContainer').setView([parseFloat(lat), parseFloat(lng)], 16);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);

                        L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map)
                            .bindPopup(address || 'Lokasi pelanggan').openPopup();

                        document.getElementById('mapAddress').textContent = address || '';
                        mapModal.show();
                        setTimeout(() => {
                            map.invalidateSize();
                        }, 200);
                    }

                    document.querySelectorAll('.btn-detail-map').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const lat = this.dataset.lat;
                            const lng = this.dataset.lng;
                            const addr = this.dataset.address;
                            openMap(lat, lng, addr);
                        });
                    });
                }

                initMapHandlers();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Select All
                document.getElementById('selectAll').addEventListener('change', function() {
                    document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = this.checked);
                });

                // Delete Selected
                document.getElementById('deleteSelected').addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(
                        cb => cb.value);
                    if (selected.length === 0) return alert('Pilih minimal satu data untuk dihapus.');
                    if (!confirm(`Yakin ingin menghapus ${selected.length} customer terpilih?`)) return;

                    fetch("{{ route('customer.bulkDelete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                alert('Gagal menghapus data!');
                            }
                        })
                        .catch(err => alert('Terjadi kesalahan.'));
                });
            });
        </script>
    @endpush
