@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Tambah Lead Baru</h1>
</div>

<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('customer.store') }}">
        @csrf

        {{-- Data Utama --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address" class="form-control" rows="2" required></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Kode Referral</label>
                <input type="text" name="referral_code" class="form-control">
            </div>
        </div>

        {{-- Data Wilayah --}}
        <h6 class="mt-4 mb-3">Wilayah</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Provinsi</label>
                <select name="province" class="form-select">
                    <option value="">Pilih Provinsi</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kota/Kabupaten</label>
                <select name="city" class="form-select">
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kecamatan</label>
                <select name="district" class="form-select">
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelurahan/Desa</label>
                <select name="village" class="form-select">
                    <option value="">Pilih Kelurahan/Desa</option>
                </select>
            </div>
        </div>

        {{-- Data Tambahan --}}
        <h6 class="mt-4 mb-3">Data Tambahan</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Division</label>
                <select name="division" class="form-select">
                    <option value="">Pilih Division</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Sales Retail">Sales Retail</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Kategori Produk</label>
                <select name="product_category" class="form-select">
                    <option value="">Pilih Kategori</option>
                    <option value="Broadband Internet">Broadband Internet</option>
                    <option value="Business Solutions">Business Solutions</option>
                    <option value="Promo Spesial Jepara">Promo Spesial Jepara</option>
                    <option value="Promo Spesial Sukoharjo">Promo Spesial Sukoharjo</option>
                    <option value="Sudirman Park">Sudirman Park</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Produk</label>
                <select name="product" class="form-select">
                    <option value="">Pilih Produk</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" placeholder="-7.797068">
            </div>
            <div class="col-md-4">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" placeholder="110.370529">
            </div>
            <div class="col-md-4">
                <label class="form-label">Coverage</label>
                <select name="coverage" class="form-select">
                    <option value="">Pilih Coverage</option>
                    <option value="Cover">Cover</option>
                    <option value="Uncover">Uncover</option>
                </select>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('customer.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
