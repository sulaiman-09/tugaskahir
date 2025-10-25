@extends('layouts.app')

@section('title', 'Tambah Customer Sudirman Park')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Tambah Customer Baru</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('sudirmanpark.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Nama Customer --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Nama Customer</h6>
                        <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white"
                            required>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Nomor Telepon</h6>
                        <input type="text" name="phone" class="form-control rounded-3 shadow-sm border-0 bg-white"
                            required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Email</h6>
                        <input type="email" name="email" class="form-control rounded-3 shadow-sm border-0 bg-white">
                    </div>

                    {{-- Alamat Tower --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Alamat Tower</h6>
                        <input type="text" name="tower" class="form-control rounded-3 shadow-sm border-0 bg-white"
                            required>
                    </div>

                    {{-- Paket --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Paket</h6>
                        <select name="package" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                            <option value="Pilih Paket">Pilih Paket</option>
                            <option value="Test Package - Rp 500.000">Test Package - Rp 500.000</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Status</h6>
                        <select name="status" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                            <option value="registration">Registration</option>
                            <option value="processed">Processed</option>
                            <option value="approved">Approved</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    {{-- Foto KTP --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Foto KTP</h6>
                        <input type="file" name="ktp" class="form-control rounded-3 shadow-sm border-0 bg-white"
                            accept="image/*,.pdf">
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Catatan</h6>
                        <textarea name="note" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="3"></textarea>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('sudirmanpark.index') }}"
                            class="btn btn-outline-secondary px-4 rounded-3 fw-semibold me-2">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Style tambahan agar mirip form User --}}
    <style>
        body {
            background-color: #f8fafc !important;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #aacbff !important;
            box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
        }

        .btn-primary {
            background-color: #0d6efd !important;
            border: none !important;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f3f5 !important;
        }
    </style>
@endsection
