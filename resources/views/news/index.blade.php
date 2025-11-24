@extends('layouts.app')

@section('title', 'News Management')

@section('content')
    <div class="container py-4">

        <div class="card border-0 shadow-sm rounded-3">
            {{-- Header --}}
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                <!-- Judul kiri -->
                <h3 class="fw-bold mb-0 text-dark">News Management</h3>

                <!-- Toolbar kanan -->
                <div class="d-flex align-items-center gap-2 justify-content-end flex-grow-1">
                    <!-- Export Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center" type="button"
                            id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('news.export.xlsx') }}">Export Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('news.export.pdf') }}">Export PDF</a></li>
                        </ul>
                    </div>

                    <!-- Add News -->
                    <a href="{{ route('news.create') }}" class="btn btn-sm d-flex align-items-center justify-content-center"
                        style="background-color: #000; border: 1px solid #000; color: #fff; width: 36px; height: 36px; padding: 6px 8px; position: relative;">
                        <i class="bi bi-file-text" style="color: #fff; font-size: 1rem;"></i>
                        <i class="bi bi-plus-lg"
                            style="color: #fff; font-size: 0.7rem; position: absolute; top: 2px; right: 2px;"></i>
                    </a>

                    <!-- Delete Selected -->
                    <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                    </button>

                    <!-- Search Form -->
                    <form action="{{ route('news.index') }}" method="GET" class="d-flex align-items-center flex-shrink-0"
                        style="min-width: 260px; max-width: 400px;">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search title, caption, or admin..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>


            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless"
                        style="min-width: 1300px; table-layout: fixed;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th style="width: 40px;"><input type="checkbox" id="selectAllNews"></th>
                                <th style="width: 50px;">ID</th>
                                <th style="max-width: 200px;">Title</th>
                                <th style="max-width: 400px;">Content</th>
                                <th style="width: 120px;">Image (Web)</th>
                                <th style="width: 120px;">Image (App)</th>
                                <th style="width: 150px;">Caption</th>
                                <th style="width: 120px;">Created Date</th>
                                <th style="width: 120px;">Admin</th>
                                <th style="width: 110px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($news as $item)
                                <tr>
                                    <td><input type="checkbox" class="select-news" value="{{ $item->news_id }}"></td>
                                    <td>{{ $item->news_id }}</td>
                                    <td class="text-start ps-3" style="min-width:200px; word-wrap: break-word;">
                                        {{ $item->news_title }}
                                    </td>
                                    <td class="text-start" style="min-width:400px; word-wrap: break-word;"
                                        title="{{ strip_tags($item->news_content) }}">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->news_content), 100) }}
                                        @if (strlen(strip_tags($item->news_content)) > 100)
                                            <button type="button" class="btn btn-link btn-sm p-0 text-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#newsContentModal{{ $item->news_id }}">
                                                Read more
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->news_image)
                                            <button type="button" class="btn btn-sm btn-outline-secondary news-preview-btn"
                                                data-preview-url="{{ Storage::url($item->news_image) }}">View</button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->news_image_app)
                                            <button type="button" class="btn btn-sm btn-outline-secondary news-preview-btn"
                                                data-preview-url="{{ Storage::url($item->news_image_app) }}">View</button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->news_image_caption ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->news_created_date)->format('d M Y') }}</td>
                                    <td>{{ $item->user ? $item->user->name : '-' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('news.edit', $item->news_id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('news.destroy', $item->news_id) }}" method="POST"
                                                class="delete-form" data-title="{{ $item->news_title }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Preview Isi Berita -->
                                <div class="modal fade" id="newsContentModal{{ $item->news_id }}" tabindex="-1"
                                    aria-labelledby="newsContentModalLabel{{ $item->news_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg rounded-4">

                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold"
                                                    id="newsContentModalLabel{{ $item->news_id }}">
                                                    {{ $item->news_title }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body pt-2"
                                                style="white-space: pre-line; font-size:15px; line-height:1.7;">
                                                {!! $item->news_content !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="10" class="text-muted text-center py-4">No news data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- Modal Preview Gambar --}}
            <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-body p-0">
                            <img src="" id="imagePreview" class="img-fluid w-100" alt="Preview">
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer: Records per page + Showing + Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-3">

                {{-- Left: Show per page + showing text --}}
                <div class="d-flex align-items-center flex-wrap gap-3">

                    <form method="GET" action="{{ route('news.index') }}" id="perPageForm"
                        class="d-flex align-items-center gap-2">
                        <label for="per_page" class="mb-0 small text-muted">Show</label>

                        <select name="per_page" id="per_page" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'All'] as $size)
                                <option value="{{ $size }}"
                                    {{ strtolower(request('per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Pertahankan query --}}
                        @foreach (request()->except('per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>

                    {{-- Showing text --}}
                    <div class="small text-muted">
                        @if ($news instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Showing {{ $news->firstItem() ?? 0 }} to {{ $news->lastItem() ?? 0 }}
                            of {{ $news->total() }} Results
                        @else
                            Showing 1 to {{ $news->count() }} of {{ $news->count() }} Results
                        @endif
                    </div>

                </div>

                {{-- Right: Pagination --}}
                <div class="right-pagination pagination-sm">
                    {{ $news->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const selectAll = document.getElementById('selectAllNews');
                const checkboxes = document.querySelectorAll('.select-news');

                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                });

                const deleteBtn = document.getElementById('deleteSelectedNews');
                deleteBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                    if (selectedIds.length === 0) {
                        alert('No news selected.');
                        return;
                    }
                    if (!confirm(
                            `Are you sure you want to delete ${selectedIds.length} selected news? This cannot be undone.`
                        )) return;

                    fetch("{{ route('news.bulkDelete') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(() => alert('Error, please try again.'));
                });

                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const title = form.dataset.title || 'berita ini';
                        if (confirm(`Yakin ingin menghapus ${title}? Aksi ini tidak dapat dibatalkan.`))
                            form.submit();
                    });
                });

            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Tombol preview gambar
                const previewButtons = document.querySelectorAll('.news-preview-btn');
                const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
                const imagePreview = document.getElementById('imagePreview');

                previewButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const url = this.dataset.previewUrl;
                        imagePreview.src = url;
                        imageModal.show();
                    });
                });

                // Checkbox select all
                const selectAll = document.getElementById('selectAllNews');
                const checkboxes = document.querySelectorAll('.select-news');

                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                });

                // Delete selected
                const deleteBtn = document.getElementById('deleteSelectedNews');
                deleteBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                    if (selectedIds.length === 0) {
                        alert('No news selected.');
                        return;
                    }
                    if (!confirm(
                            `Are you sure you want to delete ${selectedIds.length} selected news? This cannot be undone.`
                        )) return;

                    fetch("{{ route('news.bulkDelete') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(() => alert('Error, please try again.'));
                });

                // Confirm single delete
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const title = form.dataset.title || 'berita ini';
                        if (confirm(`Yakin ingin menghapus ${title}? Aksi ini tidak dapat dibatalkan.`))
                            form.submit();
                    });
                });

            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Safe guard: pastikan bootstrap ada
                if (typeof bootstrap === 'undefined') {
                    console.warn('Bootstrap JS tidak ditemukan. Pastikan bootstrap.bundle.min.js sudah di-include.');
                    return;
                }

                // Tangkap semua tombol "Read more" (yang punya data-bs-target="#newsContentModal{ID}")
                document.querySelectorAll('button[data-bs-target^="#newsContentModal"]').forEach(btn => {
                    // pastikan tombol bersifat button
                    btn.setAttribute('type', 'button');

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // ambil target (ex: #newsContentModal123)
                        const target = btn.getAttribute('data-bs-target');
                        if (!target) return;

                        // cari elemen modal
                        const modalEl = document.querySelector(target);
                        if (!modalEl) {
                            console.warn('Modal tidak ditemukan untuk target', target);
                            return;
                        }

                        // Gunakan API Bootstrap untuk membuka modal (getOrCreateInstance agar aman jika sudah ada)
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modalInstance.show();

                        // fokuskan tombol close agar aksesibilitas lebih baik
                        const closeBtn = modalEl.querySelector('[data-bs-dismiss="modal"]');
                        if (closeBtn) closeBtn.focus();
                    });
                });

                // Optional: perbaikan z-index jika modal tertutup overlay lain
                const styleId = 'fix-modal-zindex';
                if (!document.getElementById(styleId)) {
                    const s = document.createElement('style');
                    s.id = styleId;
                    s.textContent = `
            /* Pastikan modal muncul di atas elemen lain */
            .modal { z-index: 1055 !important; }
            .modal-backdrop { z-index: 1050 !important; }
        `;
                    document.head.appendChild(s);
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .table th,
            .table td {
                vertical-align: middle;
            }

            .btn-outline-secondary {
                border: 1.5px solid #6c757d;
                color: #6c757d;
                background: #fff;
                transition: 0.2s;
            }

            .btn-outline-secondary:hover {
                background: #6c757d;
                color: #fff;
            }

            .btn-primary {
                background-color: #007bff;
                border: none;
                transition: 0.2s;
            }

            .btn-primary:hover {
                background-color: #0056b3;
            }

            .table-striped>tbody>tr:nth-of-type(odd) {
                background-color: #fdfdff;
            }

            .table-striped>tbody>tr:hover {
                background-color: #eef5ff;
            }

            .fw-semibold {
                font-weight: 600;
            }

            .text-truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .table td {
                white-space: normal !important;
            }
        </style>
    @endpush
@endsection
