@extends('layouts.app')

@section('title', 'Data Banner')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4">Data Banner</h3>

        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-3">
            {{-- Header Card --}}
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center"
                            type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-print me-2"></i> Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('banner.export.excel', request()->query()) }}">Export Excel</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('banner.export.pdf', request()->query()) }}">Export
                                    PDF</a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('banner.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Banner
                    </a>
                    <button type="button" id="deleteSelected" class="btn btn-danger btn-sm ms-2">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>
                </div>

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
                                <th><input type="checkbox" id="selectAll"></th>
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
                                    <td>
                                        <input type="checkbox" class="banner-checkbox" value="{{ $banner->id }}">
                                    </td>
                                    <td class="fw-semibold">{{ $banner->name }}</td>
                                    <td>
                                        @if ($banner->path)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary banner-preview-btn"
                                                data-preview-url="{{ asset('storage/' . $banner->path) }}">
                                                View
                                            </button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($banner->path_apps)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary banner-preview-btn"
                                                data-preview-url="{{ asset('storage/' . $banner->path_apps) }}">
                                                View
                                            </button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input toggle-status" type="checkbox"
                                                data-id="{{ $banner->id }}" {{ $banner->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('banner.edit', $banner->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
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
                                    <td colspan="6" class="text-muted text-center py-4">No banner data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="d-flex align-items-center flex-wrap gap-3 mt-3">

                {{-- Show Per Page --}}
                <form method="GET" action="{{ route('banner.index') }}" class="d-flex align-items-center gap-2">
                    <label for="per_page" class="mb-0 small text-muted">Show</label>

                    <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100, 'All'] as $size)
                            <option value="{{ $size }}"
                                {{ strtolower(request('per_page', 15)) == strtolower($size) ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Keep other filters --}}
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                {{-- Showing text — DIPINDAH KE SAMPING SHOW PER PAGE --}}
                <div class="small text-muted">
                    @if ($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Showing {{ $banners->firstItem() ?? 0 }} to {{ $banners->lastItem() ?? 0 }}
                        of {{ $banners->total() }} Results
                    @else
                        Showing 1 to {{ $banners->count() }} of {{ $banners->count() }} Results
                    @endif
                </div>

                {{-- Pagination tetap di kanan --}}
                <div class="ms-auto">
                    @if ($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $banners->links() }}
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Toast notifikasi --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="successToastMessage">
                    Data successfully deleted!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Modal preview
                const bannerModalEl = document.getElementById('bannerPreviewModal');
                const bannerModal = new bootstrap.Modal(bannerModalEl);
                const spinner = document.getElementById('bannerSpinner');
                const img = document.getElementById('bannerPreviewImage');
                const errorMsg = document.getElementById('bannerPreviewError');

                document.querySelectorAll('.banner-preview-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const previewUrl = this.dataset.previewUrl;
                        spinner.style.display = 'block';
                        img.style.display = 'none';
                        errorMsg.style.display = 'none';
                        img.src = previewUrl;
                        bannerModal.show();
                        img.onload = () => {
                            spinner.style.display = 'none';
                            img.style.display = 'block';
                        };
                        img.onerror = () => {
                            spinner.style.display = 'none';
                            errorMsg.style.display = 'block';
                        };
                    });
                });

                bannerModalEl.addEventListener('hidden.bs.modal', () => {
                    img.src = '';
                    img.style.display = 'none';
                    spinner.style.display = 'none';
                    errorMsg.style.display = 'none';
                });

                // Checkbox select all
                document.getElementById('selectAll').addEventListener('change', function() {
                    document.querySelectorAll('.banner-checkbox').forEach(cb => cb.checked = this.checked);
                });

                // Delete selected banners
                document.getElementById('deleteSelected').addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.banner-checkbox:checked')).map(cb =>
                        cb.value);
                    if (selected.length === 0) return alert('Pilih minimal satu data untuk dihapus.');
                    if (!confirm(`Yakin ingin menghapus ${selected.length} banner terpilih?`)) return;

                    fetch("{{ route('banner.bulkDelete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('successToastMessage').textContent = data.message;
                                const toast = new bootstrap.Toast(document.getElementById('successToast'));
                                toast.show();
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                alert('Gagal menghapus data!');
                            }
                        })
                        .catch(err => alert('Terjadi kesalahan.'));
                });

                // Konfirmasi hapus per row
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

                // Toggle status aktif/nonaktif
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

        {{-- Modal Preview Banner --}}
        <div class="modal fade" id="bannerPreviewModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Banner Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0 d-flex align-items-center justify-content-center"
                        style="height: 80vh; background-color: #f8f9fa;">
                        <div id="bannerSpinner" class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <img id="bannerPreviewImage"
                            style="max-width:100%; max-height:100%; object-fit:contain; display:none;" />
                        <div id="bannerPreviewError" class="text-center" style="display:none;">
                            <p class="mb-0">File not found or could not be displayed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('styles')
        <style>
            .table thead th {
                color: #000;
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
