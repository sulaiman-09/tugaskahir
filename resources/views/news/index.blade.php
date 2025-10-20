@extends('layouts.app')

@section('title', 'News Management')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm p-4">

            {{-- Judul --}}
            <h4 class="fw-bold mb-3 text-dark">News Management</h4>

            {{-- Bagian atas: Add News, Export, Eye Toggle, Search --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">

                <div class="d-flex gap-2">
                    {{-- Tombol Add News --}}
                    <a href="{{ route('news.create') }}" class="btn btn-primary d-flex align-items-center gap-2"
                        style="border-radius: 8px;">
                        <i class="fa fa-plus"></i> Add News
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
                                <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light"
                                    href="#">
                                    <i class="fa fa-file-excel me-2 text-success"></i> Export XLSX
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center rounded-2 py-2 hover-bg-light"
                                    href="#">
                                    <i class="fa fa-file-csv me-2 text-info"></i> Export CSV
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Search --}}
                <form action="{{ route('news.index') }}" method="GET" class="d-flex align-items-center"
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


            {{-- Tabel Data --}}
            <div class="table-responsive mt-2">
                <table class="table table-striped table-hover align-middle text-center" id="newsTable">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Image</th>
                            <th>Image App</th>
                            <th>Caption</th>
                            <th>Created</th>
                            <th>Admin</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $item)
                            <tr>
                                <td>{{ $item->news_id }}</td>
                                <td class="fw-semibold">{{ $item->news_title }}</td>
                                <td>{{ Str::limit($item->news_content, 50) }}</td>
                                <td>
                                    @if ($item->news_image)
                                        <img src="{{ asset($item->news_image) }}" alt="News Image" width="60"
                                            class="rounded border shadow-sm">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->news_image_app)
                                        <img src="{{ asset($item->news_image_app) }}" alt="App Image" width="60"
                                            class="rounded border shadow-sm">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->news_image_caption ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->news_created_date)->format('d M Y') }}</td>
                                <td>{{ $item->user ? $item->user->name : '-' }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('news.edit', $item->news_id) }}"
                                        class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('news.destroy', $item->news_id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="return confirm('Yakin ingin hapus berita ini?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-muted text-center">No news found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $news->links() }}
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

        /* Header biru lembut */
        .table-primary th {
            background-color: #cfe2ff;
            color: #003366;
        }

        .dropdown-menu.show-on-top {
            position: absolute !important;
            right: 0 !important;
            top: auto !important;
            transform: translateY(40px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Toggle kolom sesuai checkbox
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const colIndex = parseInt(this.dataset.column) - 1;
                document.querySelectorAll('#newsTable tr').forEach(function(row) {
                    if (row.cells[colIndex]) {
                        row.cells[colIndex].style.display = checkbox.checked ? '' : 'none';
                    }
                });
            });
        });
    </script>
@endpush
