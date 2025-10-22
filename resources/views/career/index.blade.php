@extends('layouts.app')

@section('title', 'Career Management')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Career Management</h3>

        {{-- Card Utama --}}
        <div class="card border-0 shadow-sm rounded-3">
            {{-- Header Card --}}
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                {{-- Kiri: Export & Add --}}
                <div class="d-flex align-items-center gap-2">
                    {{-- Tombol Export --}}
                    <a href="{{ route('career.export', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>

                    {{-- Tambah Career --}}
                    <a href="{{ route('career.create') }}" class="btn btn-primary btn-sm">
                        + Add Career
                    </a>
                </div>

                {{-- Kanan: Search --}}
                <form action="{{ route('career.index') }}" method="GET" class="d-flex align-items-center"
                    style="min-width: 260px; max-width: 400px;">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search title, type, or location..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Education</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th style="width: 130px;">Action</th>
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
                                    <td class="text-start ps-3 fw-semibold">{{ $career->title }}</td>
                                    <td>{{ $career->type }}</td>
                                    <td>{{ $career->education_level }}</td>
                                    <td>{{ $career->location }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $career->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $career->status }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($career->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('career.edit', $career->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('career.destroy', $career->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted text-center py-4">No data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $careers->links() }}
        </div>
    </div>

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

            .btn-primary {
                background-color: #0d6efd;
                border: none;
                transition: all 0.2s ease;
            }

            .btn-primary:hover {
                background-color: #0b5ed7;
            }

            .table th,
            .table td {
                vertical-align: middle;
            }

            .table thead th {
                font-weight: 600;
                color: #212529;
            }
        </style>
    @endpush
@endsection
