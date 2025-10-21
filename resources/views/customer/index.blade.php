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

    <div class="container mt-4">
        <div class="page-header mb-3">
            <h1 class="page-title fw-bold">Data Customer</h1>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filter Tanggal --}}
        <div class="card mb-4 p-3 shadow-sm border-0 rounded-3">
            <h5 class="mb-3 fw-semibold text-primary">Filter by Date:</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($filters as $key => $label)
                    <a href="{{ route('customer.index', ['filter' => $key]) }}"
                        class="btn btn-sm {{ $currentFilter == $key ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="collapse"
                    data-bs-target="#customRange">
                    Custom Range
                </button>
            </div>

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

        {{-- Tombol Tambah --}}
        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('customer.create') }}" class="btn btn-primary">
                + Tambah Lead Baru
            </a>
        </div>

        {{-- Search dan Export --}}
        <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown">
                        <i class="fa fa-print"></i> Export
                    </button>
                    <ul class="dropdown-menu shadow border-0">
                        <li><a class="dropdown-item" href="{{ route('customer.export', request()->query()) }}"><i class="fa fa-file-csv text-info me-2"></i> Export CSV</a></li>
                    </ul>
                </div>
                <a href="{{ route('customer.create') }}" class="btn btn-success btn-sm">+ New Lead</a>
            </div>

            <form action="{{ route('customer.index') }}" method="GET" class="d-flex ms-auto" style="max-width: 420px; width:100%;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, phone, email or product"
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm ms-2"><i class="fa fa-search"></i></button>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $customer)
                        <tr>
                            <td>{{ $customers->firstItem() + $index }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->latitude }}</td>
                            <td>{{ $customer->longitude }}</td>
                            <td>
                                <select class="form-select form-select-sm coverage-dropdown" data-id="{{ $customer->id }}">
                                    <option value="">Select Coverage</option>
                                    <option value="covered" {{ $customer->coverage == 'covered' ? 'selected' : '' }}>
                                        Covered</option>
                                    <option value="uncovered" {{ $customer->coverage == 'uncovered' ? 'selected' : '' }}>
                                        Uncovered</option>
                                </select>
                            </td>
                            <td>{{ $customer->product }}</td>
                            <td>
                                <select class="form-select form-select-sm assign-dropdown" data-id="{{ $customer->id }}">
                                    <option value="">Select Division</option>
                                    <option value="marketing" {{ $customer->assign_to == 'marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="sales retail"
                                        {{ $customer->assign_to == 'sales retail' ? 'selected' : '' }}>Sales Retail
                                    </option>
                                </select>
                            </td>
                            <td>{{ $customer->submitted_at }}</td>
                            <td>{{ $customer->submitted }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('customer.destroy', $customer->id) }}" method="POST"
                                            class="delete-form"
                                            data-name="{{ $customer->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-muted text-center">Belum ada data customer</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $customers->links() }}</div>
    </div>
@push('scripts')
    <script>
        // confirm delete with modal-like prompt
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var name = form.dataset.name || 'this record';
                    if (confirm('Hapus ' + name + '? Aksi ini tidak dapat dibatalkan.')) {
                        form.submit();
                    }
                });
            });
            // enable bootstrap tooltips if available
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                var tipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tipTriggerList.map(function (el) { return new bootstrap.Tooltip(el) });
            }
        });
    </script>
@endpush
@endsection
