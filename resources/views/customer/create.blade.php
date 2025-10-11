@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
<div class="container py-4">
    <div class="page-header mb-4">
        <h1 class="page-title fw-bold">Tambah Lead Baru</h1>
        <p class="text-muted">Lengkapi data pelanggan dengan benar sebelum menyimpan.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-3 p-4">
        <form method="POST" action="{{ route('customer.store') }}">
            @csrf

            {{-- Data Utama --}}
            <h5 class="mb-3 fw-semibold text-primary">Data Utama</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Pelanggan</label>
                    <input type="text" name="name" class="form-control rounded-2" placeholder="Masukkan nama pelanggan" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nomor Telepon</label>
                    <input type="text" name="phone" class="form-control rounded-2" placeholder="Contoh: 08123456789" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-medium">Alamat Lengkap</label>
                    <textarea name="address" class="form-control rounded-2" rows="2" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" name="email" class="form-control rounded-2" placeholder="email@example.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kode Referral</label>
                    <input type="text" name="referral_code" class="form-control rounded-2" placeholder="Opsional">
                </div>
            </div>

            {{-- Data Wilayah --}}
            <h5 class="mt-4 mb-3 fw-semibold text-primary">Wilayah</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-medium">Provinsi</label>
                    <select id="province" name="province" class="form-select rounded-2" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kota/Kabupaten</label>
                    <select id="city" name="city" class="form-select rounded-2" disabled>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kecamatan</label>
                    <select id="district" name="district" class="form-select rounded-2" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kelurahan/Desa</label>
                    <select id="village" name="village" class="form-select rounded-2" disabled>
                        <option value="">Pilih Kelurahan/Desa</option>
                    </select>
                </div>
            </div>

            {{-- Data Tambahan --}}
            <h5 class="mt-4 mb-3 fw-semibold text-primary">Data Tambahan</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-medium">Division</label>
                    <select name="division" class="form-select rounded-2">
                        <option value="">Pilih Division</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales Retail">Sales Retail</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Kategori Produk</label>
                    <select name="product_category" class="form-select rounded-2">
                        <option value="">Pilih Kategori</option>
                        <option value="Broadband Internet">Broadband Internet</option>
                        <option value="Business Solutions">Business Solutions</option>
                        <option value="Promo Spesial Jepara">Promo Spesial Jepara</option>
                        <option value="Promo Spesial Sukoharjo">Promo Spesial Sukoharjo</option>
                        <option value="Sudirman Park">Sudirman Park</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Produk</label>
                    <select name="product" class="form-select rounded-2">
                        <option value="">Pilih Produk</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Latitude</label>
                    <input type="text" name="latitude" class="form-control rounded-2" placeholder="-7.797068">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Longitude</label>
                    <input type="text" name="longitude" class="form-control rounded-2" placeholder="110.370529">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Coverage</label>
                    <select name="coverage" class="form-select rounded-2">
                        <option value="">Pilih Coverage</option>
                        <option value="Cover">Cover</option>
                        <option value="Uncover">Uncover</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('customer.index') }}" class="btn btn-secondary me-3 px-4 py-2 rounded-2">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-2">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Script Wilayah Dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const villageSelect = document.getElementById('village');

    // Load Provinsi
    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(res => res.json())
        .then(provinces => {
            provinces.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                opt.dataset.id = p.id;
                provinceSelect.appendChild(opt);
            });
        });

    // Load Kabupaten berdasarkan Provinsi
    provinceSelect.addEventListener('change', function() {
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        citySelect.disabled = true;
        districtSelect.disabled = true;
        villageSelect.disabled = true;

        const provinceId = this.selectedOptions[0].dataset.id;
        if (!provinceId) return;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    opt.dataset.id = c.id;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            });
    });

    // Load Kecamatan
    citySelect.addEventListener('change', function() {
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        districtSelect.disabled = true;
        villageSelect.disabled = true;

        const cityId = this.selectedOptions[0].dataset.id;
        if (!cityId) return;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`)
            .then(res => res.json())
            .then(districts => {
                districts.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.name;
                    opt.textContent = d.name;
                    opt.dataset.id = d.id;
                    districtSelect.appendChild(opt);
                });
                districtSelect.disabled = false;
            });
    });

    // Load Kelurahan
    districtSelect.addEventListener('change', function() {
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        villageSelect.disabled = true;

        const districtId = this.selectedOptions[0].dataset.id;
        if (!districtId) return;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
            .then(res => res.json())
            .then(villages => {
                villages.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v.name;
                    opt.textContent = v.name;
                    villageSelect.appendChild(opt);
                });
                villageSelect.disabled = false;
            });
    });
});
</script>
@endsection
