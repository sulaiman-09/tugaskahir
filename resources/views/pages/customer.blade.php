@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
    <div class="container">
        <h1 class="mb-4 fw-bold">Data Customer</h1>

        <!-- Filter by Date -->
        <div class="filter-box">
            <strong>Filter by Date:</strong><br>
            <a href="{{ route('customer.index', ['filter' => 'all']) }}" class="btn-filter">All Records</a>
            <a href="{{ route('customer.index', ['filter' => 'today']) }}" class="btn-filter">Today</a>
            <a href="{{ route('customer.index', ['filter' => 'yesterday']) }}" class="btn-filter">Yesterday</a>
            <a href="{{ route('customer.index', ['filter' => 'this_week']) }}" class="btn-filter">This Week</a>
            <a href="{{ route('customer.index', ['filter' => 'last_week']) }}" class="btn-filter">Last Week</a>
            <a href="{{ route('customer.index', ['filter' => 'this_month']) }}" class="btn-filter">This Month</a>
            <a href="{{ route('customer.index', ['filter' => 'last_month']) }}" class="btn-filter">Last Month</a>
            <a href="{{ route('customer.index', ['filter' => 'last_7']) }}" class="btn-filter">Last 7 Days</a>
            <a href="{{ route('customer.index', ['filter' => 'last_30']) }}" class="btn-filter">Last 30 Days</a>

            <form method="GET" action="{{ route('customer.index') }}" class="custom-date">
                <label>Custom Date Range</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}">
                <input type="date" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn-filter">Apply</button>
            </form>
        </div>

        <!-- Tombol Tambah -->
        <div class="mb-3">
            <a href="#" class="btn btn-primary">Tambah Lead Baru</a>
        </div>

        <!-- Tabel Data Customer -->
        <div class="table-responsive">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Lokasi & Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Coverage</th>
                        <th>Produk</th>
                        <th>Assign to</th>
                        <th>Tanggal Submit</th>
                        <th>Submitted</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $c)
                        <tr>
                            <td><input type="checkbox" value="{{ $c['id'] }}"></td>
                            <td>{{ $c['name'] }}</td>
                            <td>{{ $c['phone'] }}</td>
                            <td>{{ $c['email'] }}</td>
                            <td>{{ $c['address'] }}</td>
                            <td>{{ $c['latitude'] }}</td>
                            <td>{{ $c['longitude'] }}</td>
                            <td>{{ $c['coverage'] }}</td>
                            <td>{{ $c['product'] }}</td>
                            <td>{{ $c['assign_to'] }}</td>
                            <td>{{ $c['submitted_at'] }}</td>
                            <td>{{ $c['submitted'] }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="bx bx-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Hapus"><i class="bx bx-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .customer-container { padding: 20px; }

    .customer-table {
        border-collapse: collapse;
        width: 100%;
        min-width: 1400px;
        margin-top: 20px;
        background: #fff;
        white-space: nowrap;
    }

    .customer-table th,
    .customer-table td {
        padding: 10px;
        text-align: left;
        white-space: nowrap;
        border: 1px solid #9d3242;
    }

    .customer-table th {
        background-color: #9d3242;
        color: white;
        font-weight: bold;
    }

    .customer-table tr:nth-child(even) {
        background-color: #ffe6e6;
    }

    .btn-action {
        display: inline-block;
        padding: 5px 10px;
        margin: 2px;
        border-radius: 4px;
        font-size: 13px;
        text-decoration: none;
        background-color: #9d3242;
        color: white;
    }
    .btn-action.delete { background-color: darkred; }

    .filter-box {
        border: 1px solid #ddd;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 6px;
        background: #fafafa;
    }

    .btn-filter {
        display: inline-block;
        margin: 4px 6px 6px 0;
        padding: 6px 12px;
        border: 1px solid #aaa;
        border-radius: 5px;
        background: #fff;
        color: #333;
        text-decoration: none;
        font-size: 14px;
    }
    .btn-filter:hover {
        background: #9d3242;
        color: #fff;
        border-color: #9d3242;
    }

    .custom-date { margin-top: 10px; }
    .custom-date input[type="date"] {
        padding: 4px 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .table-header {
        background-color: #800000;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: left;
    }
</style>
@endpush