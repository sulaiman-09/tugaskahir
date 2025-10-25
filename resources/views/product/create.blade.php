@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Create New Product</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Pesan error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Product Name</label>
            <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Speed</label>
            <input type="text" name="speed" class="form-control" value="{{ old('speed') }}" placeholder="e.g. 30 Mbps" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category" class="form-select" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}" placeholder="e.g. 150000">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Web Image</label>
            <input type="file" name="web_image" class="form-control">
            <small class="text-muted">Recommended: 800x600px</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Apps Image</label>
            <input type="file" name="apps_image" class="form-control">
            <small class="text-muted">Recommended: 800x600px</small>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Create Product
            </button>
            <a href="{{ route('product.index') }}" class="btn btn-secondary">
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
