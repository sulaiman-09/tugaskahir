@extends('layouts.app')

@section('title', 'Add New Division')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Add New Division</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('division.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    {{-- Data Utama --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Division Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small">Division Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white @error('name') is-invalid @enderror"
                                    placeholder="Enter division name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold small">Active Status</label>
                                <div class="form-check form-switch ms-2 mt-2">
                                    <input type="checkbox" name="status" id="status"
                                        class="form-check-input shadow-sm" value="1"
                                        {{ old('status', true) ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label fw-semibold small">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Description</h6>
                        <textarea name="description" id="description"
                            class="form-control rounded-3 shadow-sm border-0 bg-white @error('description') is-invalid @enderror"
                            placeholder="Enter short description..." rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                        <a href="{{ route('division.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            Save Division
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- STYLE TAMBAHAN --}}
    <style>
        body {
            background-color: #f8fafc !important;
        }

        .card {
            background: #ffffff;
        }

        .form-control:focus {
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

        h6 {
            font-size: 0.95rem;
        }
    </style>
@endsection
