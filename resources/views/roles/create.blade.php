@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Create New Role</h2>

    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">
            Role Information
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permissions <span class="text-danger">*</span></label>
                    <p class="small text-muted">Select the permissions for this role</p>

                    <div class="form-check mb-2">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label for="selectAll" class="form-check-label fw-semibold">Select All Permissions</label>
                    </div>

                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                        id="perm_{{ $loop->index }}" class="form-check-input permission-checkbox">
                                    <label for="perm_{{ $loop->index }}" class="form-check-label">{{ $permission }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">← Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Select All --}}
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
