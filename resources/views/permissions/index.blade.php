@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
    <div class="container-fluid mt-4">

        {{-- Bagian atas: Judul + Tombol Tambah --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="fw-bold">Permission Management</h2>
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Permission
            </a>
        </div>

        {{-- Bagian atas kedua: Eye Toggle + Search --}}
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
                            <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="1" checked> ID
                        </label>
                    </li>
                    <li>
                        <label class="dropdown-item">
                            <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="2" checked> Name
                        </label>
                    </li>
                    <li>
                        <label class="dropdown-item">
                            <input type="checkbox" class="form-check-input me-2 column-toggle" data-column="3" checked>
                            Assigned Roles
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

            {{-- Search (pojok kanan) --}}
            <form action="{{ route('permissions.index') }}" method="GET" class="d-flex align-items-center ms-auto"
                style="max-width: 250px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                    style="border-radius: 8px;">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="permissionTable" class="table table-striped table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Assigned Roles</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->roles_count }}</td> {{-- Hanya menampilkan angka --}}
                                    <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('permissions.edit', $permission->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Info bawah tabel --}}
                <div class="mt-3 text-end">
                    <small class="text-muted">Showing {{ count($permissions) }} of {{ count($permissions) }}
                        Results</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Toggle Columns --}}
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const checkboxes = document.querySelectorAll(".column-toggle");
                const table = document.querySelector("#permissionTable");

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        const colIndex = this.getAttribute("data-column");
                        const cells = table.querySelectorAll(`tr > *:nth-child(${colIndex})`);
                        cells.forEach(cell => {
                            cell.style.display = this.checked ? "" : "none";
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
