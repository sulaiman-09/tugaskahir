@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Judul -->
        <h3 class="mb-4 fw-bold text-dark">Data Banner</h3>

        <!-- Card Utama -->
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <!-- Tombol Add Banner -->
                <a href="{{ route('banner.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                    <i class="fa fa-plus me-2"></i> Add Banner
                </a>

                <!-- Search -->
                <form action="{{ route('banner.index') }}" method="GET" class="d-flex align-items-center"
                    style="max-width: 420px; width:100%;">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name"
                        value="{{ request('search') }}">
                    <a href="{{ route('banner.export', request()->query()) }}" class="btn btn-success btn-sm ms-2">Export
                        CSV</a>
                    <button type="submit"
                        class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                        style="border-radius: 8px;">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Name</th>
                            <th>Web Image</th>
                            <th>Mobile Image</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                            <tr>
                                <td class="fw-semibold">{{ $banner['name'] }}</td>
                                <td>
                                    <img src="{{ $banner['web_image'] }}" alt="Web Image" width="120"
                                        class="rounded shadow-sm border border-light">
                                </td>
                                <td>
                                    <img src="{{ $banner['mobile_image'] }}" alt="Mobile Image" width="100"
                                        class="rounded shadow-sm border border-light">
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="status{{ $loop->index }}" {{ $banner['status'] ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('banner.edit', $banner->id) }}" class="btn btn-sm btn-warning me-1"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('banner.destroy', $banner->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus banner ini?')">
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
                                <td colspan="5" class="text-muted text-center">Tidak ada data banner.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">Showing {{ $banners->firstItem() ?? 0 }} to {{ $banners->lastItem() ?? 0 }}
                        of {{ $banners->total() }} Results</small>
                </div>
                <div>{{ $banners->links() }}</div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Samakan gaya tabel dan tombol seperti Sudirman Park */
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
            color: #fff;
            background-color: #343a40 !important;
        }

        .dropdown-menu.show-on-top {
            position: absolute !important;
            left: 100% !important;
            top: 0 !important;
            margin-left: 8px !important;
            transform-origin: left center;
            animation: slideRight 0.15s ease-out;
            z-index: 3000 !important;
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
@endpush
