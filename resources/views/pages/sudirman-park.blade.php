@extends('layouts.app')

@section('content')
    <div class="customer-container">
        <h1 class="page-title">Sudirman Park - Customer Management</h1>

        {{-- Toolbar --}}
        <div class="d-flex justify-content-start align-items-center mb-4 gap-2">
            <a href="#" class="btn btn-primary">+ Tambah Customer Baru</a>
            <a href="#" class="btn btn-outline-primary">Kelola Alamat Tower</a>
            <a href="#" class="btn btn-outline-primary">Kelola Produk</a>
        </div>

        {{-- Table Wrapper --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Customer</th>
                        <th>No. Telepon</th>
                        <th>Email</th>
                        <th>Alamat Tower</th>
                        <th>Paket</th>
                        <th>ID Card</th>
                        <th>Status</th>
                        <th>Change Status</th>
                        <th>Status Update Info</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Fauzan Thoriq P</td>
                        <td>85784870207</td>
                        <td>fauzanthoriqpk@gmail.com</td>
                        <td>A-01-AA</td>
                        <td>IzPark 30 - Rp 275.000</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary">
                                View
                            </button>
                        </td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>
                            <select class="form-select form-select-sm">
                                <option value="registration">Registration</option>
                                <option value="processed">Processed</option>
                                <option value="approved" selected>Approved</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </td>
                        <td>Status sudah diperbarui</td>
                        <td>26-09-2025</td>
                        <td class="text-nowrap">
                            <button class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-primary" title="Print">
                                <i class="bi bi-printer"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Hapus">
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
    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        .customer-container {
            padding: 20px;
        }

        .btn-action {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 4px;
            font-size: 14px;
            background-color: #9d3242;
            color: white;
            border: none;
        }

        .btn-action.delete {
            background-color: darkred;
        }

        .btn-action:hover {
            opacity: 0.9;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
