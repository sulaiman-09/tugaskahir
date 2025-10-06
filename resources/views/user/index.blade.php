@extends('layouts.app')

@section('title', 'Data Division')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Data Division</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Created At</th>
                        <th width="120" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] }}</td>
                        <td>{{ now()->subDays($loop->index * 5)->format('d/m/Y H:i:s') }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <select class="form-select form-select-sm w-auto d-inline-block">
                        <option selected>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span class="ms-2">Records per page</span>
                </div>
                <p class="mb-0 small">Showing 1 to {{ count($users) }} of {{ count($users) }} Results</p>
            </div>
        </div>
    </div>
</div>
@endsection
