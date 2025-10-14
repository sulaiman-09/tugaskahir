@extends('layouts.app')

@section('title', 'Edit Content')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">Edit Content</h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('settings-content.update', $content->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title</label>
                            <input 
                                type="text" 
                                name="title"
                                id="title"
                                class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title', $content->title) }}"
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input 
                                type="text" 
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $content->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content Type --}}
                        <div class="mb-3">
                            <label for="content_type_id" class="form-label fw-semibold">Type</label>
                            <input 
                                type="number" 
                                name="content_type_id"
                                id="content_type_id"
                                class="form-control @error('content_type_id') is-invalid @enderror" 
                                value="{{ old('content_type_id', $content->content_type_id) }}"
                                required
                            >
                            @error('content_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Order --}}
                        <div class="mb-3">
                            <label for="order" class="form-label fw-semibold">Order</label>
                            <input 
                                type="number" 
                                name="order"
                                id="order"
                                class="form-control @error('order') is-invalid @enderror" 
                                value="{{ old('order', $content->order) }}"
                                required
                            >
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="form-check form-switch mb-4">
                            <input 
                                type="checkbox" 
                                name="is_active"
                                id="is_active" 
                                class="form-check-input" 
                                value="1" 
                                {{ old('is_active', $content->is_active) ? 'checked' : '' }}
                            >
                            <label for="is_active" class="form-check-label fw-semibold">
                                Active Status
                            </label>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('settings-content.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-1"></i> Update Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
