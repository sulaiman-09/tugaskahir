@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Edit Role</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions <span class="text-danger">*</span></label>
                        <p class="small text-muted">Select the permissions for this role</p>

                        <div class="form-check mb-2">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                            <label for="selectAll" class="form-check-label fw-semibold">Select All Permissions</label>
                        </div>

                        <div class="row">
                            @foreach ($permissions as $id => $permission)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $id }}"
                                            id="perm_{{ $id }}" class="form-check-input permission-checkbox"
                                            {{ in_array($id, $rolePermissions) ? 'checked' : '' }}>

                                        <label for="perm_{{ $id }}"
                                            class="form-check-label">{{ $permission }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">← Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Update Role
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
