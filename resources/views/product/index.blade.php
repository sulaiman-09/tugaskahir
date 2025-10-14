@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4 border-0">

        {{-- Judul --}}
        <h4 class="fw-bold mb-3 text-dark">Product Management</h4>

        {{-- Tombol Aksi --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                + Tambah Product Baru
            </a>
        </div>

        {{-- ======================= --}}
        {{-- TABEL 1 : CATEGORY & BENEFIT --}}
        {{-- ======================= --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                {{-- Header & Pencarian --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Category and Benefit</h6>

                    <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center" style="max-width: 250px;">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                {{-- Tabel Category --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Category Name</th>
                                <th>Slug</th>
                                <th>Short Description</th>
                                <th>Show Price</th>
                                <th>Benefits</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Broadband Internet</td>
                                <td>broadband-internet</td>
                                <td>Internet cepat dan stabil untuk rumah tangga dan bisnis kecil</td>
                                <td><span class="badge bg-success">Shown</span></td>
                                <td class="text-start">
                                    <ul class="mb-0">
                                        <li>📺 Bonus puluhan channel TV</li>
                                        <li>📶 Koneksi stabil untuk aktivitas online</li>
                                        <li>💰 Harga terjangkau untuk rumah tangga</li>
                                        <li>∞ Internet tanpa batasan kuota</li>
                                    </ul>
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Business Solutions</td>
                                <td>business-solutions</td>
                                <td>Solusi komprehensif untuk konektivitas dan entertainment bisnis</td>
                                <td><span class="badge bg-secondary">Hidden</span></td>
                                <td class="text-start">
                                    <ul class="mb-0">
                                        <li>⚡ Prioritas layanan pelanggan</li>
                                        <li>📊 Memenuhi kebutuhan bisnis modern</li>
                                        <li>🌐 IP statis untuk server dan hosting</li>
                                        <li>📡 SLA jaminan kecepatan & uptime</li>
                                    </ul>
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======================= --}}
        {{-- TABEL 2 : PRODUCT LIST --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Product List</h6>

                    <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center" style="max-width: 250px;">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search product..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                {{-- Tabel Produk --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Speed</th>
                                <th>Website Image</th>
                                <th>Apps Image</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2</td>
                                <td>Izzi Life 30</td>
                                <td>30 Mbps</td>
                                <td><img src="{{ asset('images/website1.jpg') }}" class="img-thumbnail" width="100"></td>
                                <td><img src="{{ asset('images/app1.jpg') }}" class="img-thumbnail" width="100"></td>
                                <td>Broadband Internet</td>
                                <td>Rp 166.500</td>
                                <td>16/12/2024 14:56:36</td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Dedicated Internet</td>
                                <td>10 Mbps</td>
                                <td><img src="{{ asset('images/website2.jpg') }}" class="img-thumbnail" width="100"></td>
                                <td><img src="{{ asset('images/app2.jpg') }}" class="img-thumbnail" width="100"></td>
                                <td>Business Solutions</td>
                                <td>Rp 1.700.000</td>
                                <td>20/12/2024 10:31:37</td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-warning, .btn-danger {
        border: none;
    }
    .table-primary {
        background-color: #e3f2fd !important; /* biru lembut */
        color: #0d6efd;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9fcff;
    }
    .table-hover tbody tr:hover {
        background-color: #e9f4ff !important;
    }
    .card {
        border-radius: 12px;
    }
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush
