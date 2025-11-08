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

                    <div class="row">
                        <div class="col-md-6">
                            {{-- Nama Customer --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Nama Customer</label>
                                <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white" required>
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control rounded-3 shadow-sm border-0 bg-white" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Email</label>
                                <input type="email" name="email" class="form-control rounded-3 shadow-sm border-0 bg-white">
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Status</label>
                                <select name="status" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="registration">Registrasi</option>
                                    <option value="processed">Processed</option>
                                    <option value="approved">Approved</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            {{-- Paket (dropdown dari Product) --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Paket</label>
                                <select name="package" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="">Pilih Paket</option>
                                    @if(isset($products))
                                        @foreach($products as $p)
                                            <option value="{{ $p->name }} - Rp {{ number_format($p->price,0,',','.') }}">
                                                {{ $p->name }} - Rp {{ number_format($p->price,0,',','.') }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Alamat Tower (dropdown dari sudirman_tower_addresses aktif) --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Alamat Tower</label>
                                <select name="tower" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="">Pilih Alamat Tower</option>
                                    @if(isset($addresses))
                                        @foreach($addresses as $id => $full_address)
                                            @php
                                                // Transformasi sederhana: GF -> 01, hapus digit trailing seperti '1'
                                                $display = preg_replace('/GF/', '01', $full_address);
                                                $display = preg_replace('/(\d+)$/', '', $display);
                                                $display = trim($display, "- ");
                                            @endphp
                                            <option value="{{ $display }}">{{ $display }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Foto KTP --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Foto KTP</label>
                                <input type="file" name="ktp" class="form-control rounded-3 shadow-sm border-0 bg-white" accept="image/*,.pdf">
                            </div>

                            {{-- Bukti Pembayaran --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Bukti Pembayaran</label>
                                <input type="file" name="payment_proof" class="form-control rounded-3 shadow-sm border-0 bg-white" accept="image/*,.pdf">
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Catatan</label>
                                <textarea name="note" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('sudirmanpark.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">Simpan</button>
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
