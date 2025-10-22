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
                {{-- Kiri: Tambah & Export --}}
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('division.export', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>

                    <a href="{{ route('division.create') }}" class="btn btn-primary btn-sm">
                        + Add Division
                    </a>
                </div>

                {{-- Kanan: Search --}}
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

            {{-- Isi Tabel --}}
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
                                    <td>{{ $division->customer_leads }}</td>
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
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $divisions->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-outline-secondary {
            border: 1.5px solid #6c757d;
            color: #6c757d;
            background: #fff;
            transition: all 0.2s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: #fff;
        }

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
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle status
            const checkboxes = document.querySelectorAll('.status-toggle');
            checkboxes.forEach(cb => {
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
                                status: status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Status updated');
                            }
                        });
                });
            });

            // Konfirmasi hapus
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    const name = form.dataset.name || 'this record';
                    if (confirm(`Are you sure you want to delete ${name}?`)) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
