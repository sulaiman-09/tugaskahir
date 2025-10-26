@extends('layouts.app')

@section('title', 'Edit Product Category')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Edit Product Category</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="alert alert-success rounded-3 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('product.category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <div class="row g-3">

                        {{-- Category Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label text-primary fw-semibold small">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Masukkan nama kategori"
                                   value="{{ old('name', $category->name) }}" required>
                        </div>

                        {{-- Slug --}}
                        <div class="col-md-6">
                            <label for="slug" class="form-label text-primary fw-semibold small">
                                Slug <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="slug" id="slug"
                                   class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Masukkan slug (otomatis atau manual)"
                                   value="{{ old('slug', $category->slug) }}" required>
                        </div>

                        {{-- Short Description --}}
                        <div class="col-md-12">
                            <label for="short_description" class="form-label text-primary fw-semibold small">
                                Short Description
                            </label>
                            <textarea name="short_description" id="short_description"
                                      class="form-control rounded-3 shadow-sm border-0 bg-white"
                                      rows="3" required
                                      placeholder="Tuliskan deskripsi singkat kategori">{{ old('short_description', $category->short_description) }}</textarea>
                        </div>

                        {{-- Long Description / Benefits --}}
                        <div class="col-md-12">
                            <label for="long_description" class="form-label text-primary fw-semibold small">
                                Benefits (Long Description)
                            </label>
                            <textarea name="long_description" id="long_description"
                                      class="form-control rounded-3 shadow-sm border-0 bg-white"
                                      rows="6"
                                      placeholder="Gunakan baris baru untuk setiap poin manfaat...">{{ old('long_description', $category->long_description) }}</textarea>
                            <small class="text-muted">
                                Gunakan baris baru untuk setiap poin benefit. Contoh:<br>
                                📶 Koneksi stabil untuk aktivitas online<br>
                                💰 Harga terjangkau untuk rumah tangga
                            </small>
                        </div>

                        {{-- Show Price --}}
                        <div class="col-md-12">
                            <div class="form-check mt-3 ps-1">
                                <input class="form-check-input" type="checkbox" name="show_price" id="show_price"
                                       {{ $category->show_price ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small text-secondary" for="show_price">
                                    Show Price by Default
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Update Category
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
