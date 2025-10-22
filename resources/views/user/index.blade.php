@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container py-4">

    {{-- Judul --}}
    <h3 class="fw-bold mb-4">User Management</h3>

    {{-- Card Utama --}}
    <div class="card border-0 shadow-sm rounded-3">
        {{-- Header --}}
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            {{-- Kiri: Add --}}
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add User
                </a>
            </div>

            {{-- Kanan: Search --}}
            <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                <form action="{{ route('users.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search name or email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Alert sukses --}}
        @if(session('success'))
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
                            <th style="width: 40px;">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th style="width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td class="text-start ps-3">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
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
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" 
                                              method="POST" class="delete-form" data-name="{{ $user->name }}">
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
                                <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3 px-3 pb-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- Konfirmasi Hapus --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = form.dataset.name || 'this user';
            if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
@endsection
