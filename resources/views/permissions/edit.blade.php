@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="container-fluid">
    <h2 class="fw-bold mb-4">Edit Permission</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Permission Name</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="{{ $permission->name }}" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
