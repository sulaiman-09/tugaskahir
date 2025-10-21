@extends('layouts.app')

@section('title', 'Career Management')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm p-4">

            {{-- Judul --}}
            <h4 class="fw-bold mb-3 text-dark">Career Management</h4>

            {{-- Bagian atas: Export dan Search --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                {{-- Tombol Export (di kiri) --}}
                <div class="dropdown position-relative" style="z-index: 1055;">
                    <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" type="button"
                        id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"
                        title="Export Data" style="border-radius: 8px;">
                        <i class="fa fa-print"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 show-on-top"
                        aria-labelledby="exportDropdown" style="min-width: 160px; border-radius: 10px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light"
                                href="{{ route('career.export', request()->query()) }}">
                                <i class="fa fa-file-csv me-2 text-info"></i> Export CSV
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Search (di kanan) --}}
                <form action="{{ route('career.index') }}" method="GET" class="d-flex align-items-center"
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

            {{-- Tombol Add Career --}}
            <div class="mb-3">
                <a href="{{ route('career.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Career
                </a>
            </div>

            {{-- Tabel Data Career --}}
            <div class="table-responsive mt-2">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Education</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($careers as $career)
                            <tr>
                                <td>{{ $career->id }}</td>
                                <td>
                                    <img src="{{ $career->image }}" alt="Career Image" width="60"
                                        class="rounded shadow-sm border">
                                </td>
                                <td class="fw-semibold">{{ $career->title }}</td>
                                <td>{{ $career->type }}</td>
                                <td>{{ $career->education_level }}</td>
                                <td>{{ $career->location }}</td>
                                <td>
                                    <span class="badge {{ $career->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $career->status }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($career->created_at)->format('d M Y') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('career.edit', $career->id) }}" class="btn btn-sm btn-warning me-1"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('career.destroy', $career->id) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-muted text-center">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


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

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-dark th {
            background-color: #343a40 !important;
            color: #fff !important;
        }
    </style>
@endpush
