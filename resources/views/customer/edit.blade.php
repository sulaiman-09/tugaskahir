@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container py-4">
    <div class="page-header mb-4">
        <h1 class="page-title fw-bold">Edit Data Customer</h1>
    </div>

    <div class="card shadow-sm border-0 rounded-3 p-4">
        <form method="POST" action="{{ route('customer.update', $customer->id) }}">
            @csrf
            @method('PUT')

            {{-- Data Utama --}}
            <h5 class="mb-3 fw-semibold text-primary">Data Utama</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nama Pelanggan</label>
                    <input type="text" name="name" class="form-control rounded-2" value="{{ old('name', $customer->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Nomor Telepon</label>
                    <input type="text" name="phone" class="form-control rounded-2" value="{{ old('phone', $customer->phone) }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-medium">Alamat Lengkap</label>
                    <textarea name="address" class="form-control rounded-2" rows="2" required>{{ old('address', $customer->address) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" name="email" class="form-control rounded-2" value="{{ old('email', $customer->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Kode Referral</label>
                    <input type="text" name="referral_code" class="form-control rounded-2" value="{{ old('referral_code', $customer->referral_code) }}">
                </div>
            </div>

            {{-- Data Wilayah --}}
            <h5 class="mt-4 mb-3 fw-semibold text-primary">Wilayah</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-medium">Provinsi</label>
                    <select id="province" name="province" class="form-select rounded-2">
                        <option value="">Pilih Provinsi</option>
                        <option value="{{ $customer->province }}" selected>{{ $customer->province }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kota/Kabupaten</label>
                    <select id="city" name="city" class="form-select rounded-2">
                        <option value="{{ $customer->city }}" selected>{{ $customer->city }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kecamatan</label>
                    <select id="district" name="district" class="form-select rounded-2">
                        <option value="{{ $customer->district }}" selected>{{ $customer->district }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium">Kelurahan/Desa</label>
                    <select id="village" name="village" class="form-select rounded-2">
                        <option value="{{ $customer->village }}" selected>{{ $customer->village }}</option>
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
                        <option value="Marketing" {{ $customer->division == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Sales Retail" {{ $customer->division == 'Sales Retail' ? 'selected' : '' }}>Sales Retail</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Kategori Produk</label>
                    <select name="product_category" class="form-select rounded-2">
                        <option value="">Pilih Kategori</option>
                        <option value="Broadband Internet" {{ $customer->product_category == 'Broadband Internet' ? 'selected' : '' }}>Broadband Internet</option>
                        <option value="Business Solutions" {{ $customer->product_category == 'Business Solutions' ? 'selected' : '' }}>Business Solutions</option>
                        <option value="Promo Spesial Jepara" {{ $customer->product_category == 'Promo Spesial Jepara' ? 'selected' : '' }}>Promo Spesial Jepara</option>
                        <option value="Promo Spesial Sukoharjo" {{ $customer->product_category == 'Promo Spesial Sukoharjo' ? 'selected' : '' }}>Promo Spesial Sukoharjo</option>
                        <option value="Sudirman Park" {{ $customer->product_category == 'Sudirman Park' ? 'selected' : '' }}>Sudirman Park</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Produk</label>
                    <select name="product" class="form-select rounded-2">
                        <option value="{{ $customer->product }}" selected>{{ $customer->product }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Latitude</label>
                    <input type="text" name="latitude" class="form-control rounded-2" value="{{ old('latitude', $customer->latitude) }}" placeholder="-7.797068">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Longitude</label>
                    <input type="text" name="longitude" class="form-control rounded-2" value="{{ old('longitude', $customer->longitude) }}" placeholder="110.370529">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Coverage</label>
                    <select name="coverage" class="form-select rounded-2">
                        <option value="">Pilih Coverage</option>
                        <option value="Cover" {{ $customer->coverage == 'Cover' ? 'selected' : '' }}>Cover</option>
                        <option value="Uncover" {{ $customer->coverage == 'Uncover' ? 'selected' : '' }}>Uncover</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('customer.index') }}" class="btn btn-secondary me-3 px-4 py-2 rounded-2">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-2">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Script Wilayah Dinamis (EMSIFA) --}}
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
                if (p.name === "{{ $customer->province }}") opt.selected = true;
                provinceSelect.appendChild(opt);
            });
        });

    // Event chaining untuk city, district, dan village
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.selectedOptions[0].dataset.id;
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        if (!provinceId) return;
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    opt.dataset.id = c.id;
                    if (c.name === "{{ $customer->city }}") opt.selected = true;
                    citySelect.appendChild(opt);
                });
            });
    });
});
</script>
@endsection
