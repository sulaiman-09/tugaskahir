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
                                            <img src="{{ asset($item->news_image) }}" alt="Web Image" width="70"
                                                class="rounded border shadow-sm">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->news_image_app)
                                            <img src="{{ asset($item->news_image_app) }}" alt="App Image" width="70"
                                                class="rounded border shadow-sm">
                                        @else
                                            <span class="text-muted">-</span>
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
                            <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>
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
