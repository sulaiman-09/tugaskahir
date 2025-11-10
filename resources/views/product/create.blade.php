@extends('layouts.app')

@section('title', 'Create New Product')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Create New Product</h2>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Product Name --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            {{-- Speed --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Speed</label>
                <input type="text" name="speed" class="form-control" value="{{ old('speed') }}"
                    placeholder="e.g. 30 Mbps" required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
            </div>

            {{-- Category --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Category</label>
                <select name="product_category_id" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Price --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Price</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}"
                    placeholder="e.g. 150000">
            </div>

            {{-- Web Image --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Web Image</label>
                <input type="file" name="web_image" class="form-control">
                <small class="text-muted">Recommended: 800x600px</small>
            </div>

            {{-- Apps Image --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Apps Image</label>
                <input type="file" name="apps_image" class="form-control">
                <small class="text-muted">Recommended: 800x600px</small>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Create Product
                </button>
                <a href="{{ route('product.index') }}" class="btn btn-secondary ms-2">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </form>
    </div>

@endsection

@push('styles')
    <style>
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-label {
            color: #0d6efd;
        }
    </style>
@endpush
