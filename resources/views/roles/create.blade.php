@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-dark">Create New Role</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                {{-- Role Info --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Role Information</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white" 
                                   placeholder="Enter role name" required>
                        </div>
                    </div>
                </div>

                {{-- Permissions --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Permissions</h6>
                    <p class="small text-muted ms-1">Select the permissions for this role</p>

                    <div class="form-check mb-2 ms-2">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label for="selectAll" class="form-check-label fw-semibold small">Select All Permissions</label>
                    </div>

                    <div class="row ms-2">
                        @foreach ($permissions as $id => $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $id }}" id="perm_{{ $id }}" 
                                           class="form-check-input permission-checkbox">
                                    <label for="perm_{{ $id }}" class="form-check-label small">{{ $permission }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.permission-checkbox')
            .forEach(cb => cb.checked = this.checked);
    });
</script>

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
