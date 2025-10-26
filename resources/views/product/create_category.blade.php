@extends('layouts.app')

@section('title', 'Tambah Kategori Produk')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Create New Product Category</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">

            {{-- Tampilkan Error --}}
            @if($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('product.category.store') }}" method="POST">
                @csrf

                {{-- Informasi Utama --}}
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold small">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Masukkan nama kategori" required value="{{ old('name') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold small">
                                Slug <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="slug" class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Masukkan slug (otomatis atau manual)" required value="{{ old('slug') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-primary fw-semibold small">
                                Short Description
                            </label>
                            <textarea name="short_description" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="3"
                                      placeholder="Tuliskan deskripsi singkat kategori">{{ old('short_description') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check mt-2 ps-1">
                                <input type="checkbox" name="show_price" class="form-check-input" id="showPrice" checked>
                                <label for="showPrice" class="form-check-label fw-semibold small text-secondary">
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
                        <i class="bi bi-save2 me-1"></i> Create Category
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

    /* Fokus input */
    .form-control:focus,
    .form-select:focus {
        border-color: #aacbff !important;
        box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
    }

    /* Warna tombol */
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

    /* Label form jadi biru */
    .form-label.text-primary {
        color: #0d6efd !important;
        font-weight: 600;
    }
</style>
@endsection
