@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Create New Product</h4>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Product Name --}}
                <div class="mb-3">
                    <label for="product_name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" required>
                </div>

                {{-- Speed --}}
                <div class="mb-3">
                    <label for="speed" class="form-label fw-semibold">Speed <span class="text-danger">*</span></label>
                    <input type="text" name="speed" id="speed" class="form-control" placeholder="e.g., 10 Mbps" required>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Write a short description..." required></textarea>
                </div>

                {{-- Product Web Image --}}
                <div class="mb-3">
                    <label for="web_image" class="form-label fw-semibold">Product Web Image</label>
                    <input type="file" name="web_image" id="web_image" class="form-control">
                    <small class="text-muted">Recommended size: 800x600px (JPG, PNG)</small>
                </div>

                {{-- Product Apps Image --}}
                <div class="mb-3">
                    <label for="apps_image" class="form-label fw-semibold">Product Apps Image</label>
                    <input type="file" name="apps_image" id="apps_image" class="form-control">
                    <small class="text-muted">Recommended size: 500x500px (JPG, PNG)</small>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <option value="broadband-internet">Broadband Internet</option>
                        <option value="business-solutions">Business Solutions</option>
                        <option value="promo-spesial-jepara">Promo Spesial Jepara</option>
                    </select>
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Enter price">
                    </div>
                </div>

                {{-- Sudirman Product --}}
                <div class="form-check mb-4">
                    <input type="checkbox" name="sudirman_product" value="1" class="form-check-input" id="sudirmanProduct">
                    <label class="form-check-label fw-semibold" for="sudirmanProduct">Sudirman Product</label>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save2 me-1"></i> Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
