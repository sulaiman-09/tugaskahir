@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Add New User</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    {{-- Data Utama --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">User Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Enter user name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white"
                                    placeholder="Enter user email" required>
                            </div>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Security</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" name="password"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Enter password"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white"
                                    placeholder="Re-enter password" required>
                            </div>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">User Role</h6>
                        <div class="d-flex flex-wrap gap-4 ms-2">
                            @foreach (['admin', 'sales', 'report', 'Sudirman Park'] as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="{{ $role }}"
                                        value="{{ $role }}" required>
                                    <label class="form-check-label fw-semibold small"
                                        for="{{ $role }}">{{ ucfirst($role) }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            Create User
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
