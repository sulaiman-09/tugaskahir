@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
    <div class="container py-4">
        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Role Management</h3>

        {{-- Card Utama --}}
        <div class="card border-0 shadow-sm rounded-3">
            {{-- Header --}}
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                {{-- Kiri: Add --}}
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Role
                    </a>
                    <button type="button" id="deleteSelectedRoles" class="btn btn-danger btn-sm ms-2">
                        <i class="bi bi-trash me-1"></i> Delete Selected
                    </button>
                </div>

                {{-- Kanan: Search --}}
                <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                    <form action="{{ route('roles.index') }}" method="GET" class="d-flex w-100">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search role name..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Alert sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel --}}
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th><input type="checkbox" id="selectAllRoles"></th>
                                <th>ID</th>
                                <th class="text-start ps-3">Name</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th style="width: 130px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td><input type="checkbox" class="select-role" value="{{ $role->id }}"></td>
                                    <td>{{ $role->id }}</td>
                                    <td class="text-start ps-3 fw-semibold">{{ $role->name }}</td>
                                    <td>{{ $role->permissions_count ?? 0 }}</td>
                                    <td>{{ $role->created_at ? $role->created_at->format('d/m/Y H:i') : '—' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="delete-form" data-name="{{ $role->name }}">
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
                                    <td colspan="6" class="text-center text-muted py-4">No roles found.</td>
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
                    <form method="GET" action="{{ route('roles.index') }}" class="d-flex align-items-center gap-2">
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
                        Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }}
                        of {{ $roles->total() }} Results
                    </small>

                </div>

                {{-- Right: Pagination --}}
                <div>
                    {{ $roles->links() }}
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Select all checkbox
                document.getElementById('selectAllRoles').addEventListener('change', function() {
                    document.querySelectorAll('.select-role').forEach(cb => cb.checked = this.checked);
                });

                // Bulk delete
                document.getElementById('deleteSelectedRoles').addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.select-role:checked')).map(cb => cb
                        .value);
                    if (selected.length === 0) return alert('Pilih minimal satu role.');
                    if (!confirm(`Yakin ingin menghapus ${selected.length} role terpilih?`)) return;

                    fetch("{{ route('roles.bulkDelete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) location.reload();
                            else alert(data.message);
                        })
                        .catch(() => alert('Terjadi kesalahan.'));
                });

                // Konfirmasi hapus per row
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const name = form.dataset.name || 'role ini';
                        if (confirm(`Yakin ingin menghapus ${name}?`)) form.submit();
                    });
                });
            });
        </script>
    @endpush
@endsection
