@extends('layouts.app')

@section('title', 'Data Customer')

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
        $currentFilter = request('filter', 'all'); // default 'all'
    @endphp

    <div class="container mt-4">
        <div class="page-header mb-3">
            <h1 class="page-title">Data Customer</h1>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTER BY DATE --}}
        <div class="card mb-4 p-3 shadow-sm">
            <h5 class="mb-3">Filter by Date:</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($filters as $key => $label)
                    <a href="{{ route('customer.index', ['filter' => $key]) }}"
                        class="btn btn-sm {{ $currentFilter == $key ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $label }}
                    </a>
                @endforeach

                {{-- Custom Range --}}
                <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="collapse"
                    data-bs-target="#customRange">
                    Custom Date Range
                </button>
            </div>

            {{-- Custom Range Form --}}
            <div id="customRange" class="collapse mt-3">
                <form method="GET" action="{{ route('customer.index') }}" class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">From</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" name="filter" value="custom" class="btn btn-primary w-100">Apply</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-3 d-flex gap-2">
            <!-- Tombol Modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                + Tambah Lead Baru
            </button>
        </div>

        <!-- Aksi kanan: Tombol Print dan Eye (Show/Hide Columns) -->
        <div class="d-flex align-items-center gap-2 mb-2">
            <!-- Tombol Print Dropdown -->
            <div class="dropdown position-relative" style="z-index: 1055;">
                <button class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center"
                    type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                    data-bs-display="static" title="Export Data" style="border-radius: 8px;">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top" aria-labelledby="exportDropdown"
                    style="min-width: 160px; border-radius: 10px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light" href="#">
                            <i class="fa fa-file-excel me-2 text-success"></i> Export XLSX
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light" href="#">
                            <i class="fa fa-file-csv me-2 text-info"></i> Export CSV
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tombol Eye (Show/Hide Columns) -->
            <div class="dropdown position-relative" style="z-index: 1055;">
                <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" type="button"
                    id="columnDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"
                    title="Show/Hide Columns" style="border-radius: 8px;">
                    <i class="fa fa-eye"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top" aria-labelledby="columnDropdown"
                    style="min-width: 200px; border-radius: 10px;">
                    <li class="fw-bold text-secondary px-2 mb-2">Toggle Columns</li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    @php
                        $columns = [
                            0 => 'No',
                            1 => 'Nama Pelanggan',
                            2 => 'Nomor Telepon',
                            3 => 'Email',
                            4 => 'Alamat',
                            5 => 'Latitude',
                            6 => 'Longitude',
                            7 => 'Coverage',
                            8 => 'Produk',
                            9 => 'Assign To',
                            10 => 'Submitted At',
                            11 => 'Submitted',
                            12 => 'Aksi',
                        ];
                    @endphp

                    @foreach ($columns as $index => $col)
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="form-check-input me-2 column-toggle"
                                    data-column="{{ $index }}" checked>
                                {{ $col }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
            {{-- Search (di kanan) --}}
            <form action="{{ route('career.index') }}" method="GET" class="d-flex align-items-center"
                style="max-width: 250px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                    style="border-radius: 8px;">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        {{-- Tabel Data Customer --}}
        <div class="table-responsive mt-3">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        @foreach ($columns as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $customer)
                        <tr>
                            <td class="fw-semibold">{{ $index + 1 }}</td>
                            <td>{{ $customer['name'] }}</td>
                            <td>{{ $customer['phone'] }}</td>
                            <td>{{ $customer['email'] }}</td>
                            <td>{{ $customer['address'] }}</td>
                            <td>{{ $customer['latitude'] }}</td>
                            <td>{{ $customer['longitude'] }}</td>
                            <td>{{ $customer['coverage'] }}</td>
                            <td>{{ $customer['product'] }}</td>
                            <td>{{ $customer['assign_to'] }}</td>
                            <td>{{ $customer['submitted_at'] }}</td>
                            <td>{{ $customer['submitted'] }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('customer.edit', $customer['id']) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('customer.destroy', $customer['id']) }}" method="POST"
                                        onsubmit="return confirm('Apakah yakin ingin menghapus customer ini?')"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="text-center text-muted">Belum ada data customer
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Create Customer -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('customer.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCustomerLabel">Tambah Lead Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Form Fields (Nama, Phone, Alamat, Email, Referral, Wilayah, Divisi, Produk, Coverage) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Masukkan nama pelanggan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control"
                                    placeholder="Contoh: 0812345678" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kode Referral</label>
                                <input type="text" name="referral_code" class="form-control" placeholder="Opsional">
                            </div>
                        </div>
                        <h6 class="mt-3">Wilayah</h6>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Provinsi</label>
                                <select name="province" class="form-select">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kota/Kabupaten</label>
                                <select name="city" class="form-select">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kecamatan</label>
                                <select name="district" class="form-select">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kelurahan/Desa</label>
                                <select name="village" class="form-select">
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Divisi</label>
                                <select name="division" class="form-select" required>
                                    <option value="">Pilih Divisi</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="sales_retail">Sales Retail</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kategori Produk</label>
                                <select name="product_category" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="broadband_internet">Broadband Internet</option>
                                    <option value="business_solutions">Business Solutions</option>
                                    <option value="promo_jepara">Promo Spesial Jepara</option>
                                    <option value="promo_sukoharjo">Promo Spesial Sukoharjo</option>
                                    <option value="sudirman_park">Sudirman Park</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Coverage</label>
                                <select name="coverage" class="form-select" required>
                                    <option value="">Pilih Coverage</option>
                                    <option value="cover">Cover</option>
                                    <option value="uncover">Uncover</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.column-toggle');
            const table = document.querySelector('.table');

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const colIndex = this.getAttribute('data-column');
                    const cells = table.querySelectorAll('tr > *:nth-child(' + (parseInt(colIndex) +
                        1) + ')');
                    cells.forEach(cell => {
                        cell.style.display = this.checked ? '' : 'none';
                    });
                });
            });
        });
    </script>
@endpush
