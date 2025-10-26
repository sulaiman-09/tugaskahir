@extends('layouts.app')

@section('title', 'Data Division')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Data Division</h3>

        {{-- Card utama --}}
        <div class="card border-0 shadow-sm rounded-3">

            {{-- Header aksi --}}
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('division.export', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>
                    <a href="{{ route('division.create') }}" class="btn btn-primary btn-sm">
                        + Add Division
                    </a>
                </div>

                {{-- Search --}}
                <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                    <form action="{{ route('division.index') }}" method="GET" class="d-flex w-100">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search name..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center table-striped table-hover">
                        <thead style="background-color: #e7f0ff; color: #003366; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Customer Leads</th>
                                <th>Created At</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($divisions as $division)
                                <tr>
                                    <td>{{ $division->id }}</td>
                                    <td class="text-start ps-3">{{ $division->name }}</td>
                                    <td class="text-start">{{ $division->description }}</td>
                                    <td>
                                        <input type="checkbox" class="status-toggle" data-id="{{ $division->id }}"
                                            {{ $division->status ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $division->customer_leads ?? 0 }}</td>
                                    <td>{{ $division->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('division.edit', $division->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('division.destroy', $division->id) }}" method="POST"
                                                class="delete-form" data-name="{{ $division->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted text-center py-4">No data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer: per page + pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-2">

                {{-- Records per page --}}
                <form method="GET" action="{{ route('division.index') }}" id="perPageForm"
                    class="d-flex align-items-center gap-2">
                    <label for="per_page" class="mb-0">Show</label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100, 'All'] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Pertahankan query lainnya --}}
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                {{-- Pagination --}}
                <div>
                    <small class="text-muted">
                        @if ($divisions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Showing {{ $divisions->firstItem() ?? 0 }} to {{ $divisions->lastItem() ?? 0 }}
                            of {{ $divisions->total() }} Results
                        @else
                            Showing 1 to {{ $divisions->count() }} of {{ $divisions->count() }} Results
                        @endif
                    </small>
                    <div>
                        @if ($divisions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $divisions->links() }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle status
            document.querySelectorAll('.status-toggle').forEach(cb => {
                cb.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const status = this.checked ? 1 : 0;

                    fetch(`/division/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status
                        })
                    }).then(res => res.json()).then(data => {
                        if (!data.success) alert('Update status gagal.');
                    }).catch(() => alert('Error koneksi.'));
                });
            });

            // Konfirmasi hapus
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    const name = form.dataset.name || 'this record';
                    if (confirm(`Are you sure you want to delete ${name}?`)) form.submit();
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #f7faff;
        }

        .table-striped>tbody>tr:nth-of-type(even) {
            background-color: #ffffff;
        }

        .table-hover tbody tr:hover {
            background-color: #e3edff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .status-toggle {
            transform: scale(1.2);
            cursor: pointer;
        }

        #per_page {
            min-width: 80px;
            border-radius: 8px;
            padding: 5px 10px;
            background-color: #fff;
        }

        #perPageForm {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
@endpush
