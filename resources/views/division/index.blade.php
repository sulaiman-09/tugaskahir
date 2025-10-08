@extends('layouts.app')

@section('title', 'Data Division')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4">

        {{-- Judul --}}
        <h4 class="fw-bold mb-3 text-dark">Data Division</h4>

        {{-- Tombol Aksi --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('division.create') }}"
                class="btn {{ request()->routeIs('division.create') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                + Add Division
            </a>

            {{-- Tombol Export --}}
            <div class="dropdown position-relative" style="z-index: 1055;">
                <button class="btn btn-outline-primary d-flex align-items-center justify-content-center"
                        type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                        data-bs-display="static" title="Export Data" style="border-radius: 8px;">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top"
                    aria-labelledby="exportDropdown" style="min-width: 160px; border-radius: 10px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light" href="#">
                            <i class="fa fa-file-excel me-2 text-success"></i> Export XLSX
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light" href="#">
                            <i class="fa fa-file-csv me-2 text-info"></i> Export CSV
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Search --}}
            <form action="{{ route('division.index') }}" method="GET" class="d-flex align-items-center ms-auto"
                  style="max-width: 250px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                       value="{{ request('search') }}">
                <button type="submit"
                        class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                        style="border-radius: 8px;">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Customer Leads</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($divisions as $division)
                    <tr>
                        <td>{{ $division->id }}</td>
                        <td>{{ $division->name }}</td>
                        <td>{{ $division->description }}</td>
                        <td>
                            <input type="checkbox" disabled {{ $division->status ? 'checked' : '' }}>
                        </td>
                        <td>{{ $division->customer_leads }}</td>
                        <td>{{ $division->created_at->format('d-m-Y H:i:s') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('division.edit', $division->id) }}" class="btn btn-sm btn-warning"
                               title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('division.destroy', $division->id) }}" method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $divisions->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-outline-primary {
        border: 1.5px solid #007bff;
        color: #007bff;
        background: #fff;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background: #007bff;
        color: #fff;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    /* Ganti header tabel jadi biru lembut */
    .table-primary th {
        background-color: #cfe2ff;
        color: #003366;
    }
</style>
@endpush
