@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="fw-bold">User Management</h2>

            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-2"><i class="fa fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Clear</a>
                    @endif
                </form>

                <a href="{{ route('users.create') }}" class="btn btn-primary ms-2">
                    <i class="bi bi-plus-lg"></i> Add User
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display:inline-block;" class="delete-form" data-name="{{ $user->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    @if(method_exists($users, 'links'))
                        {{ $users->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-outline-primary {
            border: 1.5px solid #007bff;
            color: #007bff;
            background: #fff;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background: #007bff;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table-dark th {
            background-color: #343a40 !important;
            color: #fff !important;
        }

        .dropdown-menu.show-on-top {
            position: absolute !important;
            right: 0 !important;
            top: auto !important;
            transform: translateY(40px);
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
        }
    </style>
@endpush
