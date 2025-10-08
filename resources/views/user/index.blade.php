@extends('layouts.app')

@section('title', 'Data Division')

@section('content')
    <div class="container-fluid px-4">
        <div class="card shadow-sm p-4">

            {{-- Judul & Tombol Add --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Data Division</h4>
                <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-lg me-1"></i> Add User
                </a>
            </div>

            {{-- Bagian atas: Eye toggle + Search --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">

                {{-- Tombol Eye (Show/Hide Columns) --}}
                <div class="dropdown position-relative" style="z-index: 1055;">
                    <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" type="button"
                        id="columnDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"
                        title="Show/Hide Columns" style="border-radius: 8px;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top"
                        aria-labelledby="columnDropdown" style="min-width: 200px; border-radius: 10px;">
                        <li class="fw-bold text-secondary px-2 mb-2">Toggle Columns</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="1" checked>
                                Name
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="2" checked>
                                Email
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="3" checked>
                                Roles
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="4" checked>
                                Created At
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="5" checked>
                                Action
                            </label>
                        </li>
                    </ul>
                </div>

                {{-- Search (pojok kanan sejajar) --}}
                <form action="{{ route('division.index') }}" method="GET" class="d-flex align-items-center ms-auto"
                    style="max-width: 250px;">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit"
                        class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                        style="border-radius: 8px;">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Created At</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td class="fw-semibold">{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['role'] }}</td>
                                <td>{{ now()->subDays($loop->index * 5)->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted text-center">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- Pagination Info --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <select class="form-select form-select-sm w-auto d-inline-block">
                        <option selected>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span class="ms-2">Records per page</span>
                </div>
                <p class="mb-0 small text-muted">
                    Showing 1 to {{ count($users) }} of {{ count($users) }} Results
                </p>
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
