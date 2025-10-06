@extends('layouts.app') {{-- Atau layout yang Anda gunakan --}}

@section('content')
<div class="container">
    <h1>Create Product</h1>
    
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Product Name --}}
        <div class="form-group mb-3">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>

        {{-- Speed --}}
        <div class="form-group mb-3">
            <label for="speed">Speed</label>
            <input type="text" name="speed" class="form-control" required>
        </div>

        {{-- Description --}}
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        {{-- Product Web Image --}}
        <div class="form-group mb-3">
            <label for="web_image">Product Web Image</label>
            <input type="file" name="web_image" class="form-control-file">
        </div>

        {{-- Product Apps Image --}}
        <div class="form-group mb-3">
            <label for="apps_image">Product Apps Image</label>
            <input type="file" name="apps_image" class="form-control-file">
        </div>

        {{-- Category --}}
        <div class="form-group mb-3">
            <label for="category">Category</label>
            <select name="category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="broadband-internet">Broadband Internet</option>
                <option value="business-solutions">Business Solutions</option>
                <option value="promo-spesial-jepara">Promo Spesial Jepara</option>
            </select>
        </div>

        {{-- Price --}}
        <div class="form-group mb-3">
            <label for="price">Price</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="price" class="form-control">
            </div>
        </div>

        {{-- Sudirman Product --}}
        <div class="form-check mb-4">
            <input type="checkbox" name="sudirman_product" value="1" class="form-check-input" id="sudirmanProduct">
            <label class="form-check-label" for="sudirmanProduct">Sudirman Product</label>
        </div>

        {{-- Buttons --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Create Product</button>
        </div>
    </form>
</div>
@endsection
