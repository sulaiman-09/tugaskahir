@extends('layouts.app')

@section('title', 'Settings Content')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Settings Content</h3>

        {{-- Card Utama --}}
        <div class="card border-0 shadow-sm rounded-3">
            {{-- Header --}}
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                {{-- Kiri: Export & Add --}}
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('settings-content.export', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>
                </div>

                <a href="{{ route('settings-content.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Content
                </a>
            </div>

            {{-- Kanan: Search --}}
            <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                <form action="{{ route('settings-content.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search title or name" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-2">
                        <i class="fa fa-search"></i>
                    </button>
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
                            <th style="width: 40px;">No</th>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Type ID</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Icon</th>
                            <th style="width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contents as $index => $content)
                            <tr>
                                <td>{{ $contents->firstItem() + $index }}</td>
                                <td class="text-start ps-3">{{ $content->title }}</td>
                                <td>{{ $content->name }}</td>
                                <td>{{ $content->content_type_id }}</td>
                                <td>{{ $content->order }}</td>
                                <td>
                                    @if ($content->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($content->image)
                                        <img src="{{ asset('storage/' . $content->image) }}" width="50" height="50"
                                            class="rounded">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($content->icon)
                                        <img src="{{ asset('storage/' . $content->icon) }}" width="40" height="40"
                                            class="rounded">
                                    @else
                                        <span class="text-muted">No Icon</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('settings-content.edit', $content->id) }}"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('settings-content.destroy', $content->id) }}" method="POST"
                                            class="delete-form" data-name="{{ $content->title }}">
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
                                <td colspan="9" class="text-center text-muted py-4">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3 px-3 pb-3">
            {{ $contents->links() }}
        </div>
    </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-form').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const name = form.dataset.name || 'this record';
                        if (confirm(
                                `Are you sure you want to delete "${name}"? This action cannot be undone.`
                                )) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
