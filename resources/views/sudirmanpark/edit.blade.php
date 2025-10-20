@extends('layouts.app')

@section('title', 'Edit Customer Sudirman Park')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Customer - Sudirman Park</h1>
</div>

<div class="card shadow-sm p-4">
    <form action="{{ route('sudirmanpark.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama Customer --}}
        <div class="mb-3">
            <label class="form-label">Nama Customer *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
        </div>

        {{-- Nomor Telepon --}}
        <div class="mb-3">
            <label class="form-label">Nomor Telepon *</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
        </div>

        {{-- Alamat Tower --}}
        <div class="mb-3">
            <label class="form-label">Alamat Tower *</label>
            <input type="text" name="tower" class="form-control" value="{{ old('tower', $customer->tower) }}" required>
        </div>

        {{-- Paket --}}
        <div class="mb-3">
            <label class="form-label">Paket *</label>
            <select name="package" class="form-select" required>
                <option value="">Pilih Paket</option>
                <option value="Test Package - Rp 500.000" {{ old('package', $customer->package) == 'Test Package - Rp 500.000' ? 'selected' : '' }}>
                    Test Package - Rp 500.000
                </option>
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status *</label>
            <select name="status" class="form-select" required>
                <option value="registration" {{ old('status', $customer->status) == 'registration' ? 'selected' : '' }}>Registration</option>
                <option value="processed" {{ old('status', $customer->status) == 'processed' ? 'selected' : '' }}>Processed</option>
                <option value="approved" {{ old('status', $customer->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="cancelled" {{ old('status', $customer->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        {{-- Foto KTP --}}
        <div class="mb-3">
            <label class="form-label">Foto KTP</label>
            @if ($customer->ktp)
                <div class="mb-2">
                    <a href="{{ asset('storage/ktp/'.$customer->ktp) }}" target="_blank" class="text-primary">
                        Lihat KTP saat ini
                    </a>
                </div>
            @endif
            <input type="file" name="ktp" class="form-control" accept="image/*,.pdf">
        </div>

        {{-- Tampilkan di daftar --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="visible" class="form-check-input" id="visibleCheck" {{ old('visible', $customer->visible) ? 'checked' : '' }}>
            <label class="form-check-label" for="visibleCheck">Tampilkan di daftar</label>
        </div>

        {{-- Catatan --}}
        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="note" class="form-control" rows="3">{{ old('note', $customer->note) }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-end">
            <a href="{{ route('sudirmanpark.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
