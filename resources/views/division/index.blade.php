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
                    <button type="button" id="deleteSelected" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>
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
                            <tr class="fw-semibold text-center">
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
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
                                    <td class="text-center">
                                        <input type="checkbox" class="select-item" value="{{ $division->id }}">
                                    </td>
                                    <td>{{ $division->id }}</td>
                                    <td class="text-start ps-3">{{ $division->name }}</td>
                                    <td class="text-start">{{ $division->description }}</td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status-toggle" type="checkbox"
                                                data-id="{{ $division->id }}" {{ $division->status ? 'checked' : '' }}>
                                        </div>
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
                                    <td colspan="8" class="text-muted text-center py-4">No data found.</td>
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
                            <option value="{{ $size }}"
                                {{ strtolower(request('per_page', 15)) == strtolower($size) ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Pertahankan query lain --}}
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

        // Select / deselect all
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.select-item');

        selectAll?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Delete selected
        document.getElementById('deleteSelected')?.addEventListener('click', function() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one division to delete.');
                return;
            }

            if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected division(s)?`)) return;

            fetch('{{ route('division.bulkDelete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert(data.message || 'Failed to delete.');
                })
                .catch(() => alert('Error connecting to server.'));
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

        /* Toggle switch ukuran normal */
        .form-switch .form-check-input {
            cursor: pointer;
            transition: 0.3s;
        }

        .form-switch .form-check-input:checked {
            background-color: #0d6efd;
            /* Biru */
        }
    </style>
@endpush
