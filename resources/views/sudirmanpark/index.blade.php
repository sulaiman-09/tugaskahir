@extends('layouts.app')

@section('title', 'Sudirman Park - Customer Management')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm p-4">

            {{-- Judul --}}
            <h4 class="fw-bold mb-3 text-dark">Sudirman Park - Customer Management</h4>

            {{-- Tombol Aksi --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('sudirmanpark.create') }}"
                    class="btn {{ request()->routeIs('sudirmanpark.create') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    + Tambah Customer Baru
                </a>

                <a href="{{ route('sudirmanpark.alamat') }}"
                    class="btn {{ request()->routeIs('sudirmanpark.alamat') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    Kelola Alamat Tower
                </a>

                <a href="{{ route('product.index') }}"
                    class="btn {{ request()->routeIs('product.*') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    Kelola Produk
                </a>
            </div>

            {{-- Tombol Print & Eye --}}
            <div class="d-flex gap-2 mb-2">
                {{-- Print Dropdown --}}
                <div class="dropdown position-relative" style="z-index: 1055;">
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center"
                        type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                        data-bs-display="static" title="Export Data">
                        <i class="fa fa-print"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top"
                        aria-labelledby="exportDropdown" style="min-width: 160px; border-radius: 10px;">
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

                {{-- Eye Button --}}
                <div class="dropdown position-relative" style="z-index: 1055;">
                    <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" type="button"
                        id="columnDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"
                        title="Show/Hide Columns">
                        <i class="fa fa-eye"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top"
                        aria-labelledby="columnDropdown" style="min-width: 200px; border-radius: 10px;">
                        <li class="fw-bold text-secondary px-2 mb-2">Toggle Columns</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @php
                            $columns = [
                                0 => 'No',
                                1 => 'Nama Customer',
                                2 => 'No. Telepon',
                                3 => 'Email',
                                4 => 'Alamat Tower',
                                5 => 'Paket',
                                6 => 'ID Card',
                                7 => 'Status',
                                8 => 'Change Status',
                                9 => 'Status Update Info',
                                10 => 'Tanggal Dibuat',
                                11 => 'Aksi',
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
                    <button type="submit"
                        class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                        style="border-radius: 8px;">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            @foreach ($columns as $col)
                                <th>{{ $col }}</th>
                            @endforeach
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
                                <button class="btn btn-sm btn-outline-secondary">View</button>
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
                                <button class="btn btn-sm btn-warning" title="Edit"><i
                                        class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-primary" title="Print"><i class="bi bi-printer"></i></button>
                                <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .btn-outline-primary {
            border: 1.5px solid #007bff;
            color: #007bff;
            background: #fff;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background: #007bff;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
