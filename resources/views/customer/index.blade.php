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

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Data Customer Leads</h3>

        {{-- Filter --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    @foreach ($filters as $key => $label)
                        <a href="{{ route('customer.index', ['filter' => $key]) }}"
                            class="btn btn-sm {{ $currentFilter == $key ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    <button type="button" class="btn btn-outline-primary btn-sm ms-1" data-bs-toggle="collapse"
                        data-bs-target="#customRange">
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
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('customer.export', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>
                    <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm">
                        + Tambah Lead Baru
                    </a>
                    {{-- 🔹 Tombol toggle latitude/longitude --}}
                    <button type="button" id="deleteSelected" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>
                    <button id="toggle-coordinates" type="button" class="btn btn-outline-dark btn-sm">
                        Tampilkan Koordinat
                    </button>
                </div>
                <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                    <form action="{{ route('customer.index') }}" method="GET" class="d-flex w-100">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search name, phone, email or product" value="{{ request('search') }}">
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
                                <th><input type="checkbox" id="selectAll"></th> <!-- Tambahan checkbox select all -->
                                <th style="width: 40px;">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor Telepon</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Coverage</th>
                                <th>Produk</th>
                                <th>Division</th>
                                <th>Dibuat</th>
                                <th>Detail</th>
                                <th style="width: 110px;">Aksi</th>
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
                                        {{ $customer_lead->product ? $customer_lead->product->name : '-' }}</td>
                                    <td>{{ $customer_lead->productCategory ? $customer_lead->productCategory->name : '-' }}
                                    </td>
                                    <td>{{ $customer_lead->created_at ? $customer_lead->created_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info btn-detail-map"
                                            data-lat="{{ $customer_lead->latitude }}"
                                            data-lng="{{ $customer_lead->longitude }}"
                                            data-address="{{ e($customer_lead->customer_address) }}">
                                            Detail
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
                                    <td colspan="14" class="text-muted text-center py-4">Belum ada data customer</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <form method="GET" action="{{ route('customer.index') }}" id="perPageForm"
                    class="d-flex align-items-center">
                    <label for="per_page" class="mb-0 me-2">Show</label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm"
                        onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100, 'All'] as $size)
                            <option value="{{ $size }}"
                                {{ strtolower(request('per_page', 15)) == strtolower($size) ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Pertahankan search & filter --}}
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $customer_leads->links() }}
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
                    const latitudeCols = document.querySelectorAll('td:nth-child(6), th:nth-child(6)');
                    const longitudeCols = document.querySelectorAll('td:nth-child(7), th:nth-child(7)');
                    // default: coordinates hidden
                    let visible = false;
                    latitudeCols.forEach(col => col.style.display = 'none');
                    longitudeCols.forEach(col => col.style.display = 'none');
                    toggleButton.textContent = 'Tampilkan Koordinat';

                    toggleButton.addEventListener('click', function() {
                        visible = !visible;
                        latitudeCols.forEach(col => col.style.display = visible ? '' : 'none');
                        longitudeCols.forEach(col => col.style.display = visible ? '' : 'none');
                        toggleButton.textContent = visible ? 'Sembunyikan Koordinat' : 'Tampilkan Koordinat';
                    });
                });
            </script>
        @endpush

    @endsection

    @push('styles')
        <style>
            #per_page {
                min-width: 80px;
                border-radius: 8px;
                padding: 5px 10px;
                z-index: 10;
                position: relative;
                background-color: #fff;
            }

            #perPageForm {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .pagination {
                margin-bottom: 0;
            }

            .d-flex.flex-wrap.gap-2 {
                gap: 10px !important;
            }
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
