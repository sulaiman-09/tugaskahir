@extends('layouts.app')

@section('title', 'Edit Content')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3 text-white bg-primary p-3 rounded-top">Edit Content</h4>

        <form action="{{ route('settings-content.update', $content->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $content->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $content->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Type</label>
                <input type="text" name="content_type_id" class="form-control" value="{{ old('content_type_id', $content->content_type_id) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Order</label>
                <input type="number" name="order" class="form-control" value="{{ old('order', $content->order) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Upload Image</label>
                <input type="file" name="image_path" class="form-control" accept="image/*">
                @if($content->image_path)
                    <div class="mt-2">
                        <img src="{{ asset('uploads/' . $content->image_path) }}" alt="Uploaded Image" class="img-thumbnail" style="max-height:150px;">
                    </div>
                @endif
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $content->is_active ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="is_active">Active Status</label>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('settings-content.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Content
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
