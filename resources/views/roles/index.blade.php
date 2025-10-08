@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
    <div class="container-fluid px-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Role Management</h2>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Role
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

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top" aria-labelledby="columnDropdown"
                    style="min-width: 200px; border-radius: 10px;">
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
                            Permissions
                        </label>
                    </li>
                    <li>
                        <label class="dropdown-item">
                            <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="3" checked>
                            Created At
                        </label>
                    </li>
                    <li>
                        <label class="dropdown-item">
                            <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="4" checked>
                            Action
                        </label>
                    </li>
                </ul>
            </div>

            {{-- Search (pojok kanan sejajar) --}}
            <form action="{{ route('roles.index') }}" method="GET" class="d-flex align-items-center ms-auto"
                style="max-width: 250px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                    style="border-radius: 8px;">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        {{-- Card Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role['id'] }}</td>
                                    <td class="fw-semibold">{{ $role['name'] }}</td>
                                    <td>{{ $role['permissions_count'] ?? 0 }}</td>
                                    <td>{{ $role['created_at'] ?? '—' }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Pagination --}}
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
                        Showing {{ count($roles) }} of {{ count($roles) }} Results
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.column-toggle');

            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    const columnIndex = parseInt(this.getAttribute('data-column'));
                    const table = document.querySelector('table');
                    const rows = table.querySelectorAll('tr');

                    rows.forEach(row => {
                        const cells = row.querySelectorAll('th, td');
                        if (cells[columnIndex]) {
                            cells[columnIndex].style.display = this.checked ? '' : 'none';
                        }
                    });
                });
            });
        });
    </script>
@endpush

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

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-dark th {
            background-color: #343a40 !important;
            color: #fff !important;
        }
    </style>
@endpush
