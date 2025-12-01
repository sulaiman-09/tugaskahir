@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 user-page">


        {{-- Card --}}
        <div class="card border-0 shadow-sm rounded-3">

            {{-- Header --}}
            <div class="card border-0 shadow-sm rounded-3">

                {{-- Header --}}
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">

                    {{-- Kiri: Judul --}}
                    <h3 class="fw-bold mb-0">User Management</h3>

                    {{-- Kanan: Tombol + Search --}}
                    <div class="d-flex align-items-center gap-2">

                        {{-- Tombol Add --}}
                        {{-- Tombol Add --}}
                        <a href="{{ route('users.create') }}" class="btn btn-sm d-flex align-items-center gap-1"
                            style="background-color: #000; border: 1px solid #000; color: #fff; padding: 5px 6px; border-radius: 6px;">
                            <i class="bi bi-person" style="color: #fff; font-size: 1rem;"></i>
                            <i class="bi bi-plus-lg" style="color: #fff; font-size: 0.9rem;"></i>
                        </a>

                        {{-- Tombol Delete --}}
                        <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn"
                            style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                            <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                        </button>

                        {{-- Search --}}
                        <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                            <form action="{{ route('users.index') }}" method="GET" class="d-flex w-100">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search name or email..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary btn-sm ms-2"><i
                                        class="fa fa-search"></i></button>
                                @if (request('search'))
                                    <a href="{{ route('users.index') }}"
                                        class="btn btn-outline-secondary btn-sm ms-2">Clear</a>
                                @endif
                                <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                            </form>
                        </div>

                    </div>
                </div>


                {{-- Alert --}}
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
                                    <th><input type="checkbox" id="selectAllUsers"></th>
                                    <th>No</th>
                                    <th class="text-start ps-3">Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th style="width: 130px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td><input type="checkbox" class="select-user" value="{{ $user->id }}"></td>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td class="text-start ps-3 fw-semibold">{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-primary">Admin</span>
                                            @elseif($user->role === 'editor')
                                                <span class="badge bg-info text-dark">Editor</span>
                                            @else
                                                <span class="badge bg-secondary">User</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit"><i
                                                        class="bi bi-pencil-square"></i></a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="delete-form" data-name="{{ $user->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" title="Delete"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No user data found.</td>
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
                        <form method="GET" action="{{ route('users.index') }}" class="d-flex align-items-center gap-2">
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
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                            of {{ $users->total() }} Results
                        </small>
                    </div>

                    {{-- Right: Pagination --}}
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Select all
                    document.getElementById('selectAllUsers').addEventListener('change', function() {
                        document.querySelectorAll('.select-user').forEach(cb => cb.checked = this.checked);
                    });

                    // Bulk delete
                    document.getElementById('deleteSelectedUsers').addEventListener('click', function() {
                        const selected = Array.from(document.querySelectorAll('.select-user:checked')).map(cb => cb
                            .value);
                        if (selected.length === 0) return alert('Pilih minimal satu user.');
                        if (!confirm(`Yakin ingin menghapus ${selected.length} user terpilih?`)) return;

                        fetch("{{ route('users.bulkDelete') }}", {
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

                    // Delete per row
                    document.querySelectorAll('.delete-form').forEach(form => {
                        form.addEventListener('submit', e => {
                            e.preventDefault();
                            if (confirm(`Yakin ingin menghapus ${form.dataset.name}?`)) form.submit();
                        });
                    });
                });
            </script>
        @endpush
    @endsection

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @endpush
