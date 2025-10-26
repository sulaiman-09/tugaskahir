@extends('layouts.app')

@section('title', 'Create New Product')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Create New Product</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success rounded-3 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan error --}}
            @if($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- Product Name --}}
                    <div class="col-md-6">
                        <label class="form-label text-primary fw-semibold small">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="product_name"
                               class="form-control rounded-3 shadow-sm border-0 bg-white"
                               value="{{ old('product_name') }}" required
                               placeholder="Masukkan nama produk">
                    </div>

                    {{-- Speed --}}
                    <div class="col-md-6">
                        <label class="form-label text-primary fw-semibold small">Speed <span class="text-danger">*</span></label>
                        <input type="text" name="speed"
                               class="form-control rounded-3 shadow-sm border-0 bg-white"
                               value="{{ old('speed') }}" required
                               placeholder="e.g. 30 Mbps">
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label class="form-label text-primary fw-semibold small">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control rounded-3 shadow-sm border-0 bg-white"
                                  rows="3" required placeholder="Tuliskan deskripsi produk">{{ old('description') }}</textarea>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label class="form-label text-primary fw-semibold small">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6">
                        <label class="form-label text-primary fw-semibold small">Price</label>
                        <input type="number" name="price"
                               class="form-control rounded-3 shadow-sm border-0 bg-white"
                               value="{{ old('price') }}" placeholder="e.g. 150000">
                    </div>

                    {{-- Web Image --}}
                    <div class="col-md-6">
                        <label class="form-label text-primary fw-semibold small">Web Image</label>
                        <input type="file" name="web_image"
                               class="form-control rounded-3 shadow-sm border-0 bg-white">
                        <small class="text-muted">Recommended: 800x600px</small>
                    </div>

                    {{-- Apps Image --}}
                    <div class="col-md-6">
                        <label class="form-label text-primary fw-semibold small">Apps Image</label>
                        <input type="file" name="apps_image"
                               class="form-control rounded-3 shadow-sm border-0 bg-white">
                        <small class="text-muted">Recommended: 800x600px</small>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    .form-label.text-primary {
        color: #0d6efd !important;
        font-weight: 600;
    }
</style>
@endsection
