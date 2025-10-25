@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Create New Product Category</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product.category.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Category Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control" required value="{{ old('slug') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Short Description</label>
            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="show_price" class="form-check-input" id="showPrice" checked>
            <label for="showPrice" class="form-check-label">Show Price by default</label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Create Category
        </button>
        <a href="{{ route('product.index') }}" class="btn btn-secondary ms-2">
            <i class="fa fa-arrow-left me-1"></i> Back
        </a>
    </form>
</div>
@endsection
