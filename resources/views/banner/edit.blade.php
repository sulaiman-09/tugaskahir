@extends('layouts.app')

@section('title', 'Edit Banner')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-dark">Edit Banner</h5>
                <a href="{{ route('banner.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold">
                    Back
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Data Utama --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Banner Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Banner Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ $banner->name }}"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                                    <option value="1" {{ $banner->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$banner->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Banner Images</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Web Image</label><br>
                                @if ($banner->web_image)
                                    <img src="{{ asset('storage/'.$banner->web_image) }}" class="rounded shadow-sm mb-2" width="140">
                                @endif
                                <input type="file" name="web_image"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Mobile Image</label><br>
                                @if ($banner->mobile_image)
                                    <img src="{{ asset('storage/'.$banner->mobile_image) }}" class="rounded shadow-sm mb-2" width="100">
                                @endif
                                <input type="file" name="mobile_image"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white">
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                        <a href="{{ route('banner.index') }}"
                            class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            Cancel
                        </a>
                        <button type="submit"
                            class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            Update Banner
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

        .form-control:focus, .form-select:focus {
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
