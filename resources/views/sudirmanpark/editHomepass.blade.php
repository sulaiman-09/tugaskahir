@extends('layouts.app')

@section('title', 'Edit Homepass - Sudirman Park')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h1 class="page-title mb-0">Edit Homepass - Sudirman Park</h1>
        <a href="{{ route('sudirmanpark.alamat') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body bg-light-subtle p-4">
        <form action="{{ route('sudirmanpark.updateHomepass', $address->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Tower --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Tower</h6>
                <input type="text" name="tower" id="tower"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ $address->tower }}" required>
            </div>

            {{-- Floor --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Floor</h6>
                <input type="text" name="floor" id="floor"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ $address->floor }}" required>
            </div>

            {{-- Unit --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Unit</h6>
                <input type="text" name="unit" id="unit"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ $address->unit }}" required>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Status</h6>
                <select name="status" id="status" class="form-select rounded-3 shadow-sm border-0 bg-white">
                    <option value="aktif" {{ $address->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $address->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol Simpan --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                    <i class="bi bi-save me-1"></i> Update
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

        .card-body {
            border-radius: 15px;
        }
    </style>
@endpush
