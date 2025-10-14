@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit User</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password <small>(kosongkan jika tidak ingin ganti)</small></label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Role</label>
                    <div class="d-flex flex-wrap gap-4">
                        @php
                            $roles = ['admin', 'sales', 'report', 'Sudirman Park'];
                        @endphp
                        @foreach ($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" value="{{ $role }}" {{ $user->role === $role ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $role }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
