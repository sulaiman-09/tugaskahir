@extends('layouts.app')

@section('title', 'Tambah Customer Sudirman Park')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Tambah Customer Baru - Sudirman Park</h1>
</div>

<div class="card shadow-sm p-4">
    <form action="{{ route('sudirmanpark.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Customer *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon *</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Tower *</label>
            <input type="text" name="tower" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Paket *</label>
            <select name="package" class="form-select" required>
                <option value="Test Package - Rp 500.000">Test Package - Rp 500.000</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status *</label>
            <select name="status" class="form-select" required>
                <option value="registration">Registration</option>
                <option value="processed">Processed</option>
                <option value="approved">Approved</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto KTP</label>
            <input type="file" name="ktp" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="note" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('sudirmanpark.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
