@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Product Category</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('product.category.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $category->slug) }}" required>
        </div>

        <div class="mb-3">
            <label for="short_description" class="form-label fw-semibold">Short Description</label>
            <textarea name="short_description" id="short_description" class="form-control" rows="3" required>{{ old('short_description', $category->short_description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="long_description" class="form-label fw-semibold">Benefits (Long Description)</label>
            <textarea name="long_description" id="long_description" class="form-control" rows="6" placeholder="Gunakan baris baru untuk setiap poin manfaat...">{{ old('long_description', $category->long_description) }}</textarea>
            <small class="text-muted">Gunakan baris baru untuk setiap poin benefit. Contoh:<br>📶 Koneksi stabil untuk aktivitas online<br>💰 Harga terjangkau untuk rumah tangga</small>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="show_price" id="show_price" {{ $category->show_price ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="show_price">Tampilkan Harga (Show Price)</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="{{ route('product.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
