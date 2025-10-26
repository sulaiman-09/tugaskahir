@extends('layouts.app')

@section('title', 'Data Banner')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Data Banner</h3>

        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                {{-- Kiri: Export & Tambah --}}
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('sudirmanpark.exportHomepass', request()->query()) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>
                    <a href="{{ route('banner.create') }}" class="btn btn-primary btn-sm">
                        + Add Banner
                    </a>
                </div>

                {{-- Kanan: Search --}}
                <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                    <form action="{{ route('banner.index') }}" method="GET" class="d-flex w-100">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name"
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 700;">
                            <tr class="text-dark">
                                <th>Name</th>
                                <th>Web Image</th>
                                <th>Mobile Image</th>
                                <th>Status</th>
                                <th style="width: 150px;">Action</th>
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
                                            <input class="form-check-input toggle-status" type="checkbox"
                                                data-id="{{ $banner->id }}" {{ $banner->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('banner.edit', $banner->id) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('banner.destroy', $banner->id) }}" method="POST"
                                                class="delete-form" data-name="{{ $banner->name }}">
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
                                    <td colspan="5" class="text-muted text-center py-4">Tidak ada data banner.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Pagination + Records per page --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-2">

                {{-- Records per page --}}
                <div class="d-flex align-items-center">
                    <form method="GET" action="{{ route('banner.index') }}" id="perPageForm"
                        class="d-flex align-items-center gap-2">
                        <label for="per_page" class="mb-0">Show</label>
                        <select name="per_page" id="per_page" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'All'] as $size)
                                <option value="{{ $size }}"
                                    {{ request('per_page', 15) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Pertahankan search --}}
                        @foreach (request()->except('per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>

                {{-- Pagination --}}
                <div>
                    <small class="text-muted">
                        @if ($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Showing {{ $banners->firstItem() ?? 0 }} to {{ $banners->lastItem() ?? 0 }}
                            of {{ $banners->total() }} Results
                        @else
                            Showing 1 to {{ $banners->count() }} of {{ $banners->count() }} Results
                        @endif
                    </small>
                    <div>
                        @if ($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $banners->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Konfirmasi hapus
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const name = form.dataset.name || 'banner ini';
                        if (confirm(
                                `Yakin ingin menghapus ${name}? Aksi ini tidak dapat dibatalkan.`)) {
                            form.submit();
                        }
                    });
                });

                // Toggle status
                document.querySelectorAll('.toggle-status').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const id = this.dataset.id;
                        const is_active = this.checked ? 1 : 0;

                        fetch(`/banner/${id}/toggle-status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    is_active
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (!data.success) {
                                    alert('Gagal mengubah status.');
                                    checkbox.checked = !checkbox.checked;
                                }
                            })
                            .catch(() => {
                                alert('Terjadi error koneksi.');
                                checkbox.checked = !checkbox.checked;
                            });
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .table thead th {
                color: #000000;
                font-weight: 600;
            }

            .table tbody tr:nth-child(even) {
                background-color: #f8faff;
            }

            .table tbody tr:hover {
                background-color: #e6f0ff;
                transition: 0.2s;
            }

            .btn-primary {
                background-color: #007bff;
                border: none;
            }

            .btn-primary:hover {
                background-color: #0069d9;
            }

            .btn-warning,
            .btn-danger {
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                border-bottom: 1px solid #dee2e6;
            }

            #per_page {
                min-width: 80px;
                border-radius: 8px;
                padding: 5px 10px;
                z-index: 10;
                position: relative;
                background-color: #fff;
            }

            #perPageForm {
                display: flex;
                align-items: center;
                gap: 8px;
            }
        </style>
    @endpush
@endsection
