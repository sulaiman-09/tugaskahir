@extends('layouts.app')

@section('title', 'Tambah Homepass - Sudirman Park')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h1 class="page-title mb-0">Tambah Homepass - Sudirman Park</h1>
    <a href="{{ route('sudirmanpark.alamat') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm p-4">
    <form action="{{ route('sudirmanpark.storeHomepass') }}" method="POST">
        @csrf

        {{-- Tower --}}
        <div class="mb-3">
            <label for="tower" class="form-label fw-semibold">Tower <span class="text-danger">*</span></label>
            <input type="text" name="tower" id="tower" class="form-control" placeholder="Contoh: A, B, C" required>
        </div>

        {{-- Floor --}}
        <div class="mb-3">
            <label for="floor" class="form-label fw-semibold">Floor <span class="text-danger">*</span></label>
            <input type="text" name="floor" id="floor" class="form-control" placeholder="Contoh: 01, 02, 07, 15" required>
        </div>

        {{-- Unit --}}
        <div class="mb-3">
            <label for="unit" class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
            <input type="text" name="unit" id="unit" class="form-control" placeholder="Contoh: AA, BB, 01, 02" required>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label fw-semibold">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="Aktif" selected>Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .page-title {
        font-weight: 600;
    }

    .card {
        border-radius: 15px;
    }
</style>
@endpush
