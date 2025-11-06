@extends('layouts.app')

@section('title', 'News Management')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4 text-dark">News Management</h3>

        <div class="card border-0 shadow-sm rounded-3">

            {{-- Header --}}
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('news.export.csv') }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="fa fa-print me-2"></i> Export CSV
                    </a>
                    <a href="{{ route('news.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                        <i class="fa fa-plus me-2"></i> Add News
                    </a>
                </div>

                {{-- Search --}}
                <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                    <form action="{{ route('news.index') }}" method="GET" class="d-flex w-100">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search title, caption, or admin..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th style="width: 50px;">ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Image (Web)</th>
                                <th>Image (App)</th>
                                <th>Caption</th>
                                <th>Created Date</th>
                                <th>Admin</th>
                                <th style="width: 110px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($news as $item)
                                <tr>
                                    <td>{{ $item->news_id }}</td>
                                    <td class="text-start ps-3 fw-semibold">{{ $item->news_title }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($item->news_content, 50) }}</td>
                                    <td>
                                        @if ($item->news_image)
                                            <button type="button" class="btn btn-sm btn-outline-secondary news-preview-btn"
                                                data-preview-url="{{ asset($item->news_image) }}">
                                                View
                                            </button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->news_image_app)
                                            <button type="button" class="btn btn-sm btn-outline-secondary news-preview-btn"
                                                data-preview-url="{{ asset($item->news_image_app) }}">
                                                View
                                            </button>
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
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted text-center py-4">No news data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer: Records per page + Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-2">
                {{-- Records per page --}}
                <form method="GET" action="{{ route('news.index') }}" id="perPageForm"
                    class="d-flex align-items-center gap-2">
                    <label for="per_page" class="mb-0">Show</label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100, 'All'] as $size)
                            <option value="{{ $size }}"
                                {{ strtolower(request('per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Pertahankan query search --}}
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                {{-- Pagination --}}
                <div>
                    {{ $news->links() }}
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
                        const title = form.dataset.title || 'berita ini';
                        if (confirm(
                                `Yakin ingin menghapus ${title}? Aksi ini tidak dapat dibatalkan.`)) {
                            form.submit();
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal HTML
                const modalHtml = `
                    <div class="modal fade" id="newsPreviewModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">News Image Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="height: 80vh; background-color: #f8f9fa;">
                                    <div id="newsSpinner" class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <img id="newsPreviewImage" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;" />
                                    <div id="newsPreviewError" class="text-center" style="display: none;">
                                        <p class="mb-0">File not found or could not be displayed.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);

                const newsModalEl = document.getElementById('newsPreviewModal');
                const newsModal = new bootstrap.Modal(newsModalEl);
                const spinner = document.getElementById('newsSpinner');
                const img = document.getElementById('newsPreviewImage');
                const errorMsg = document.getElementById('newsPreviewError');

                // Button preview logic
                document.querySelectorAll('.news-preview-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const previewUrl = this.dataset.previewUrl;

                        img.style.display = 'none';
                        errorMsg.style.display = 'none';
                        spinner.style.display = 'block';

                        newsModal.show();

                        img.src = previewUrl;
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

                // Reset modal ketika ditutup
                newsModalEl.addEventListener('hidden.bs.modal', function() {
                    img.src = '';
                    img.style.display = 'none';
                    errorMsg.style.display = 'none';
                    spinner.style.display = 'none';
                });
            });
        </script>
    @endpush

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

            .table-striped>tbody>tr:nth-of-type(odd) {
                background-color: #fdfdff;
            }

            .table-striped>tbody>tr:hover {
                background-color: #eef5ff;
            }

            .fw-semibold {
                font-weight: 600;
            }
        </style>
    @endpush
@endsection
