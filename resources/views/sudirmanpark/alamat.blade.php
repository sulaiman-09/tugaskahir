@extends('layouts.app')

@section('title', 'Homepass - Sudirman Park')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h1 class="page-title mb-0">Homepass - Sudirman Park</h1>
    <a href="{{ route('sudirmanpark.index') }}" class="btn btn-secondary">
        Back to Customer
    </a>
</div>

<div class="card shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-primary">
                + Tambah Homepass
            </a>

            <button class="btn btn-outline-secondary">
                <i class="bi bi-download"></i>
            </button>

            <button class="btn btn-outline-secondary">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>

        <div class="input-group" style="width: 250px;">
            <input type="text" class="form-control" placeholder="Search...">
            <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Tower</th>
                    <th>Floor</th>
                    <th>Unit</th>
                    <th>Alamat Lengkap</th>
                    <th>Jumlah Customer</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td><input type="checkbox"></td>
                    <td>A</td>
                    <td>GF</td>
                    <td>AA1</td>
                    <td>A-GF-AA1</td>
                    <td>1</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>26/08/2025 14:33:26</td>
                    <td>
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>A</td>
                    <td>GF</td>
                    <td>AB1</td>
                    <td>A-GF-AB1</td>
                    <td>1</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>26/08/2025 14:33:26</td>
                    <td>
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .page-title {
        font-weight: 600;
    }

    .btn-outline-secondary {
        border: 1.5px solid #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .card {
        border-radius: 15px;
    }
</style>
@endpush
