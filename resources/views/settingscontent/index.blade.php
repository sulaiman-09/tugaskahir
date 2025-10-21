@extends('layouts.app')

@section('title', 'Settings Content')

@section('content')
<div class="container py-4">

    {{-- Header + Add Content --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Settings Content</h4>
        <a href="{{ route('settings-content.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Content
        </a>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search dan Export --}}
    <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown">
                    <i class="fa fa-print"></i> Export
                </button>
                <ul class="dropdown-menu shadow border-0">
                    <li>
                        <a class="dropdown-item" href="{{ route('settings-content.export', request()->query()) }}">
                            <i class="fa fa-file-csv text-info me-2"></i> Export CSV
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <form action="{{ route('settings-content.index') }}" method="GET" class="d-flex ms-auto" style="max-width: 320px; width:100%;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search title or name"
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary btn-sm ms-2"><i class="fa fa-search"></i></button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Name</th>
                    <th>Type ID</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Icon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $index => $content)
                    <tr>
                        <td>{{ $contents->firstItem() + $index }}</td>
                        <td>{{ $content->title }}</td>
                        <td>{{ $content->name }}</td>
                        <td>{{ $content->content_type_id }}</td>
                        <td>{{ $content->order }}</td>
                        <td>
                            @if($content->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($content->image)
                                <img src="{{ asset('storage/' . $content->image) }}" width="50" height="50" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            @if($content->icon)
                                <img src="{{ asset('storage/' . $content->icon) }}" width="40" height="40" class="rounded">
                            @else
                                <span class="text-muted">No Icon</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('settings-content.edit', $content->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('settings-content.destroy', $content->id) }}" method="POST" class="d-inline delete-form"
                                  data-name="{{ $content->title }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">{{ $contents->links() }}</div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // confirm delete
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var name = form.dataset.name || 'this record';
                if (confirm('Are you sure you want to delete "' + name + '"? This action cannot be undone.')) {
                    form.submit();
                }
            });
        });

        // bootstrap tooltips
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tipTriggerList.map(function (el) { return new bootstrap.Tooltip(el) });
        }
    });
</script>
@endpush

@endsection
