@extends('layouts.app')

@section('title', 'Create Content')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Add New Content</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">
            <form action="{{ route('settings-content.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Data Utama --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-dark border-start border-3 ps-2 mb-3">Main Data</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Content Type <span class="text-danger">*</span></label>
                            <input type="number" name="content_type_id"
                                class="form-control rounded-3 shadow-sm border-0 bg-white"
                                placeholder="Enter content type ID" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                class="form-control rounded-3 shadow-sm border-0 bg-white"
                                placeholder="Enter content title" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control rounded-3 shadow-sm border-0 bg-white"
                                placeholder="Enter content name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Order</label>
                            <input type="number" name="order"
                                class="form-control rounded-3 shadow-sm border-0 bg-white" value="0">
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-dark border-start border-3 ps-2 mb-3">Description</h6>
                    <textarea name="description" class="form-control rounded-3 shadow-sm border-0 bg-white"
                        rows="4" placeholder="Enter description..."></textarea>
                </div>

                {{-- Media --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-dark border-start border-3 ps-2 mb-3">Media</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Image</label>
                            <input type="file" name="image"
                                class="form-control rounded-3 shadow-sm border-0 bg-white">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Icon</label>
                            <input type="file" name="icon"
                                class="form-control rounded-3 shadow-sm border-0 bg-white">
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-dark border-start border-3 ps-2 mb-3">Status</h6>
                    <div class="form-check form-switch ms-3">
                        <input type="checkbox" name="is_active" class="form-check-input" checked>
                        <label class="form-check-label fw-semibold small text-dark ms-2">Active</label>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('settings-content.index') }}"
                        class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    {{-- STYLE TAMBAHAN --}}
    <style>
        body {
            background-color: #f8fafc !important;
        }

        .card {
            background: #ffffff;
        }

        .form-control:focus {
            border-color: #aacbff !important;
            box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
        }

        .btn-primary {
            background-color: #0d6efd !important;
            border: none !important;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f3f5 !important;
        }

        h6 {
            font-size: 0.95rem;
        }
    </style>
@endsection
