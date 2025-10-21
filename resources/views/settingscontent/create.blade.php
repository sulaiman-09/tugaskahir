@extends('layouts.app')

@section('title', 'Create Content')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0">Create Content</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('settings-content.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Content Type</label>
                        <input type="number" name="content_type_id" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Order</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Icon</label>
                        <input type="file" name="icon" class="form-control">
                    </div>
                    <div class="col-md-12 form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" checked>
                        <label class="form-check-label fw-semibold">Active</label>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('settings-content.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create Content</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
