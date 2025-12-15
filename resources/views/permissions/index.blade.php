@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 permission-page">

        <div class="card border-0 shadow-sm rounded-3">

            {{-- Header Card --}}
            <div class="card-header bg-white py-3 permission-header">
                <!-- Judul kiri -->
                <h3 class="fw-bold mb-0 text-dark permission-title">Permission Management</h3>

                <!-- Spacer -->
                <div class="flex-grow-1"></div>

                <!-- Toolbar kanan -->
                <div class="permission-toolbar">
                    <!-- Add Permission -->
                    <a href="{{ route('permissions.create') }}"
                        class="btn btn-sm toolbar-btn-square d-flex align-items-center justify-content-center toolbar-item"
                        style="background-color: #000; border: 1px solid #000; color: #fff; position: relative;">
                        <i class="bi bi-key" style="color: #fff; font-size: 1rem;"></i>
                        <i class="bi bi-plus-lg"
                            style="color: #fff; font-size: 0.7rem; position: absolute; top: 2px; right: 2px;"></i>
                    </a>

                    <!-- Delete Selected -->
                    <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn toolbar-item"
                        title="Delete selected permissions" aria-label="Delete selected permissions"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i>
                        <span class="btn-label">Delete Selected</span>
                    </button>

                    <!-- Search Form -->
                    <form action="{{ route('permissions.index') }}" method="GET" class="permission-search">
                        <div class="input-group input-group-sm w-100">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search permission name..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i>
                            </button>
                            @if (request('search'))
                                <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary btn-sm">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color:#f8f9fa; border-bottom:2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th>
                                    <input type="checkbox" id="selectAllPermissions">
                                </th>
                                <th style="width:60px;">No</th>
                                <th>Name</th>
                                <th>Assigned Roles</th>
                                <th>Created At</th>
                                <th style="width:130px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permissions as $index => $permission)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="select-permission" value="{{ $permission->id }}">
                                    </td>
                                    <td>{{ $permissions->firstItem() + $index }}</td>
                                    <td class="text-start ps-3">{{ $permission->name }}</td>
                                    <td>{{ $permission->roles_count ?? 0 }}</td>
                                    <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('permissions.edit', $permission->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                method="POST" class="delete-form" data-name="{{ $permission->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No permission data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer: Show per page + Showing results + Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-2">

                {{-- Left: Show per page + Showing results --}}
                <div class="d-flex align-items-center gap-3 flex-wrap">

                    {{-- Show per page --}}
                    <form method="GET" action="{{ route('permissions.index') }}" class="d-flex align-items-center gap-2">
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <label for="per_page" class="mb-0">Show</label>
                        <select name="per_page" id="per_page" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'All'] as $size)
                                <option value="{{ $size }}"
                                    {{ request('per_page', 15) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{-- Showing results --}}
                    <small class="text-muted">
                        Showing {{ $permissions->firstItem() ?? 0 }} to {{ $permissions->lastItem() ?? 0 }}
                        of {{ $permissions->total() }} Results
                    </small>

                </div>

                {{-- Right: Pagination --}}
                <div>
                    {{ $permissions->links() }}
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Konfirmasi hapus per row
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const name = form.dataset.name || 'this permission';
                        if (confirm(
                                `Are you sure you want to delete "${name}"? This action cannot be undone.`
                            )) {
                            form.submit();
                        }
                    });
                });

                // Select All checkbox
                const selectAll = document.getElementById('selectAllPermissions');
                const checkboxes = document.querySelectorAll('.select-permission');

                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                });

                // Bulk Delete
                const deleteBtn = document.getElementById('deleteSelectedPermissions');
                deleteBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                    if (selectedIds.length === 0) {
                        alert('No permissions selected.');
                        return;
                    }
                    if (!confirm(
                            `Are you sure you want to delete ${selectedIds.length} selected permissions? This cannot be undone.`
                        )) return;

                    fetch("{{ route('permissions.bulkDelete') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(() => alert('Error, please try again.'));
                });
            });
        </script>
    @endpush
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/permission.css') }}">
@endpush
