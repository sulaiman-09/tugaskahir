@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Edit Customer Data</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">
            <form method="POST" action="{{ route('customer.update', $customer->id) }}">
                @csrf
                @method('PUT')

                {{-- Data Utama --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Main Data</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                        <label class="form-label fw-semibold small">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('customer_name', $customer->customer_name) }}" required>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fw-semibold small">Telephone Number <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('customer_phone', $customer->customer_phone) }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Full Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="2" required>{{ old('address', $customer->address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fw-semibold small">Email </label>
                        <input type="email" name="email" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('email', $customer->email) }}">
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fw-semibold small">Referral Code </label>
                        <input type="text" name="referral_code" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('referral_code', $customer->referral_code) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Customer Address </label>
                            <textarea name="customer_address" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="2">{{ old('customer_address', $customer->customer_address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Latitude</label>
                            <input type="text" name="latitude" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('latitude', $customer->latitude) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Longitude</label>
                            <input type="text" name="longitude" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('longitude', $customer->longitude) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Region ID</label>
                            <input type="text" name="region_id" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ old('region_id', $customer->region_id) }}">
                        </div>
                    </div>
                </div>

                {{-- Data Wilayah --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Regional Data</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Province <span class="text-danger">*</span></label>
                            <select id="province" name="province" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                <option value="{{ $customer->province }}" selected>{{ $customer->province ?? 'Pilih Provinsi' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">City/District</label>
                            <select id="city" name="city" class="form-select rounded-3 shadow-sm border-0 bg-white">
                                <option value="{{ $customer->city }}" selected>{{ $customer->city ?? 'Pilih Kota/Kabupaten' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Sub - district</label>
                            <select id="district" name="district" class="form-select rounded-3 shadow-sm border-0 bg-white">
                                <option value="{{ $customer->district }}" selected>{{ $customer->district ?? 'Pilih Kecamatan' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Village/District</label>
                            <select id="village" name="village" class="form-select rounded-3 shadow-sm border-0 bg-white">
                                <option value="{{ $customer->village }}" selected>{{ $customer->village ?? 'Pilih Kelurahan/Desa' }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Data Tambahan --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Additional Data</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                        <label class="form-label fw-semibold small">Division</label>
                        <select name="division" class="form-select rounded-3 shadow-sm border-0 bg-white">
                        <option value="">Pilih Division</option>
                        <option value="Marketing" {{ $customer->division == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Sales Retail" {{ $customer->division == 'Sales Retail' ? 'selected' : '' }}>Sales Retail</option>
                        </select>
                        </div>
                        <div class="col-md-4">
                        <label class="form-label fw-semibold small">Product Category</label>
                        <select name="product_category" class="form-select rounded-3 shadow-sm border-0 bg-white">
                        <option value="">Select Category</option>
                        <option value="Broadband Internet" {{ $customer->product_category == 'Broadband Internet' ? 'selected' : '' }}>Broadband Internet</option>
                        <option value="Business Solutions" {{ $customer->product_category == 'Business Solutions' ? 'selected' : '' }}>Business Solutions</option>
                        <option value="Promo Spesial Jepara" {{ $customer->product_category == 'Promo Spesial Jepara' ? 'selected' : '' }}>Promo Spesial Jepara</option>
                        <option value="Promo Spesial Sukoharjo" {{ $customer->product_category == 'Promo Spesial Sukoharjo' ? 'selected' : '' }}>Promo Spesial Sukoharjo</option>
                        <option value="Sudirman Park" {{ $customer->product_category == 'Sudirman Park' ? 'selected' : '' }}>Sudirman Park</option>
                        </select>
                        </div>
                        <div class="col-md-4">
                        <label class="form-label fw-semibold small">Product</label>
                        <select name="product" class="form-select rounded-3 shadow-sm border-0 bg-white">
                        <option value="">Select product</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ $customer->product_id == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Coverage</label>
                            <select name="coverage" class="form-select rounded-3 shadow-sm border-0 bg-white">
                                <option value="">Select Coverage</option>
                                <option value="Cover" {{ $customer->coverage == 'Cover' ? 'selected' : '' }}>Cover</option>
                                <option value="Uncover" {{ $customer->coverage == 'Uncover' ? 'selected' : '' }}>Uncover</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Wilayah --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const villageSelect = document.getElementById('village');

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

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.selectedOptions[0]?.dataset.id;
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        citySelect.disabled = true; districtSelect.disabled = true; villageSelect.disabled = true;
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
                citySelect.disabled = false;
            });
    });

    citySelect.addEventListener('change', function() {
        const cityId = this.selectedOptions[0]?.dataset.id;
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        if (!cityId) return;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`)
            .then(res => res.json())
            .then(districts => {
                districts.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.name;
                    opt.textContent = d.name;
                    opt.dataset.id = d.id;
                    if (d.name === "{{ $customer->district }}") opt.selected = true;
                    districtSelect.appendChild(opt);
                });
                districtSelect.disabled = false;
            });
    });

    districtSelect.addEventListener('change', function() {
        const districtId = this.selectedOptions[0]?.dataset.id;
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        if (!districtId) return;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
            .then(res => res.json())
            .then(villages => {
                villages.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v.name;
                    opt.textContent = v.name;
                    if (v.name === "{{ $customer->village }}") opt.selected = true;
                    villageSelect.appendChild(opt);
                });
                villageSelect.disabled = false;
            });
    });
});
</script>

{{-- Style Tambahan --}}
<style>
    body {
        background-color: #f8fafc !important;
    }

    .card {
        background: #ffffff;
    }

    h6 {
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #aacbff !important;
        box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
    }

    .btn-primary {
        background-color: #0d6efd !important;
        border: none !important;
        transition: 0.2s;
    }

    .btn-primary:hover {
        background-color: #0b5ed7 !important;
    }

    .btn-outline-secondary:hover {
        background-color: #f1f3f5 !important;
    }
</style>
@endsection
