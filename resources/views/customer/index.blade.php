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
        $currentFilter = request('filter', 'all');
    @endphp

    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Data Customer</h3>

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
                    <button type="button" class="btn btn-outline-dark btn-sm ms-1" data-bs-toggle="collapse"
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
                {{-- Kiri: Export & Tambah --}}
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('customer.export', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>

                    <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm">
                        + Tambah Lead Baru
                    </a>
                </div>

                {{-- Kanan: Search --}}
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

            {{-- Tabel --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th style="width: 40px;">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor Telepon</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Coverage</th>
                                <th>Produk</th>
                                <th>Assign To</th>
                                <th>Submitted At</th>
                                <th>Submitted</th>
                                <th style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $customer)
                                <tr>
                                    <td>{{ $customers->firstItem() + $index }}</td>
                                    <td class="text-start ps-3">{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td class="text-start">{{ $customer->address }}</td>
                                    <td>{{ $customer->latitude }}</td>
                                    <td>{{ $customer->longitude }}</td>
                                    <td>
                                        <select class="form-select form-select-sm coverage-dropdown"
                                            data-id="{{ $customer->id }}">
                                            <option value="">Select Coverage</option>
                                            <option value="covered"
                                                {{ $customer->coverage == 'covered' ? 'selected' : '' }}>Covered</option>
                                            <option value="uncovered"
                                                {{ $customer->coverage == 'uncovered' ? 'selected' : '' }}>Uncovered
                                            </option>
                                        </select>
                                    </td>
                                    <td class="text-start">{{ $customer->product }}</td> {{-- Pastikan produk text-start agar tidak numpuk --}}
                                    <td>
                                        <select class="form-select form-select-sm assign-dropdown"
                                            data-id="{{ $customer->id }}">
                                            <option value="">Select Division</option>
                                            <option value="Marketing"
                                                {{ $customer->assign_to == 'Marketing' ? 'selected' : '' }}>Marketing
                                            </option>
                                            <option value="Sales Retail"
                                                {{ $customer->assign_to == 'Sales Retail' ? 'selected' : '' }}>Sales Retail
                                            </option>
                                        </select>
                                    </td>
                                    <td>{{ $customer->submitted_at }}</td>
                                    <td>{{ $customer->submitted }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('customer.edit', $customer->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('customer.destroy', $customer->id) }}" method="POST"
                                                class="delete-form" data-name="{{ $customer->name }}">
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
                                    <td colspan="13" class="text-muted text-center py-4">Belum ada data customer</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

            {{-- Records per page --}}
            <div class="d-flex align-items-center">
                <form method="GET" action="{{ route('customer.index') }}" id="perPageForm"
                    class="d-flex align-items-center">
                    <label for="per_page" class="mb-0">Show</label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100, 'All'] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>
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

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $customers->links() }}
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
    @endsection

    @push('styles')
        <style>
            /* Biar dropdown ga ketutup dan ukurannya pas */
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
    @endpush
