@extends('layouts.app')

@section('title', 'Edit Homepass - Sudirman Park')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h1 class="page-title mb-0">Edit Homepass - Sudirman Park</h1>
    <a href="{{ route('sudirmanpark.alamat') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm p-4">
    <form action="{{ route('sudirmanpark.updateHomepass', $address->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tower" class="form-label fw-semibold">Tower</label>
            <input type="text" name="tower" id="tower" class="form-control" value="{{ $address->tower }}" required>
        </div>

        <div class="mb-3">
            <label for="floor" class="form-label fw-semibold">Floor</label>
            <input type="text" name="floor" id="floor" class="form-control" value="{{ $address->floor }}" required>
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label fw-semibold">Unit</label>
            <input type="text" name="unit" id="unit" class="form-control" value="{{ $address->unit }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fw-semibold">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="aktif" {{ $address->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $address->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
