@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Product</h2>

    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Speed</label>
            <input type="text" name="speed" class="form-control" value="{{ old('speed', $product->speed) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $cat->id == $product->product_category_id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Web Image</label><br>
            @if($product->web_image)
                <img src="{{ asset('storage/' . $product->web_image) }}" width="100" class="mb-2">
            @endif
            <input type="file" name="web_image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Apps Image</label><br>
            @if($product->apps_image)
                <img src="{{ asset('storage/' . $product->apps_image) }}" width="100" class="mb-2">
            @endif
            <input type="file" name="apps_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('product.index') }}" class="btn btn-secondary ms-2">Back</a>
    </form>
</div>
@endsection
