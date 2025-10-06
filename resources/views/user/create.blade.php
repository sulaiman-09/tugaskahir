@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Data User</h2>
        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="">
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control bg-light" id="email" name="email" value="admin@lifemedia.id">
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control bg-light" id="password" name="password" placeholder="•••">
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                {{-- Role --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Role</label>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="admin" value="admin">
                            <label class="form-check-label" for="admin">admin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="sales" value="sales">
                            <label class="form-check-label" for="sales">sales</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="report" value="report">
                            <label class="form-check-label" for="report">report</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="sudirman" value="Sudirman Park">
                            <label class="form-check-label" for="sudirman">Sudirman Park</label>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
