@extends('layouts.app')

@section('title', 'Create Permission')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Add New Permission</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                {{-- Data Permission --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Permission Information</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Permission Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control rounded-3 shadow-sm border-0 bg-white" 
                                placeholder="Enter permission name" 
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        Create Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- STYLE TAMBAHAN --}}
<style>
    body { background-color: #f8fafc !important; }
    .card { background: #ffffff; }
    .form-control:focus {
        border-color: #aacbff !important;
        box-shadow: 0 0 5px rgba(99,162,255,0.35) !important;
    }
    .btn-primary {
        background-color: #0d6efd !important;
        border: none !important;
        transition: background-color 0.2s ease;
    }
    .btn-primary:hover { background-color: #0b5ed7 !important; }
    .btn-outline-secondary:hover { background-color: #f1f3f5 !important; }
    h6 { font-size: 0.95rem; }
</style>
@endsection
