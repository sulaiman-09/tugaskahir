@extends('layouts.app')

@section('content')
<div class="customer-container">
    <h1>Sudirman Park - Customer Management</h1>

    {{-- Toolbar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group">
            <a href="#" class="btn btn-primary">Tambah Customer Baru</a>
            <a href="#" class="btn btn-secondary">Kelola Alamat Tower</a>
            <a href="#" class="btn btn-info text-white">Kelola Produk</a>
        </div>
        <div class="d-flex">
            <input type="text" class="form-control" placeholder="Search...">
        </div>
    </div>

    {{-- Table Wrapper --}}
    <div class="table-responsive">
        <table class="customer-table">
            <thead>
                <tr>
                    <th>Nama Customer</th>
                    <th>No. Telepon</th>
                    <th>Email</th>
                    <th>Alamat Tower</th>
                    <th>Paket</th>
                    <th>Id Card</th>
                    <th>Status</th>
                    <th>Change Status</th>
                    <th>Status Update Info</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Fauzan Thoriq P</td>
                    <td>85784870207</td>
                    <td>fauzanthoriqpk@gmail.com</td>
                    <td>A-01-AA</td>
                    <td>IzPark 30 - Rp 275.000</td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#idCardModalFauzan">
                            View
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="idCardModalFauzan" tabindex="-1"
                            aria-labelledby="idCardLabelFauzan" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('images/idcard.png') }}" alt="ID Card Fauzan"
                                            class="img-fluid rounded shadow">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-success">Approved</span></td>
                    <td>
                        <select class="form-select">
                            <option value="registration">Registration</option>
                            <option value="processed">Processed</option>
                            <option value="approved" selected>Approved</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                    <td>Status sudah diperbarui</td>
                    <td>26-09-2025</td>
                    <td>
                        <button class="btn-action"><i class="bx bx-edit"></i></button>
                        <button class="btn-action"><i class="bx bx-printer"></i></button>
                        <button class="btn-action delete"><i class="bx bx-trash"></i></button>
                    </td>
                </tr>
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
        min-width: 1400px; /* biar scroll kalau banyak kolom */
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush