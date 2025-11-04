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
                                        @if ($career->image_path)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary career-preview-btn"
                                                data-preview-url="{{ asset($career->image_path) }}">
                                                View
                                            </button>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
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


            {{-- Footer: per page + pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-2">

                {{-- Records per page --}}
                <div class="d-flex align-items-center">
                    <form method="GET" action="{{ route('career.index') }}" id="perPageForm"
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

                        {{-- Pertahankan query search --}}
                        @foreach (request()->except('per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal HTML
            const modalHtml = `
                <div class="modal fade" id="careerPreviewModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Career Image Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="height: 80vh; background-color: #f8f9fa;">
                                <div id="careerSpinner" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <img id="careerPreviewImage" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;" />
                                <div id="careerPreviewError" class="text-center" style="display: none;">
                                    <p class="mb-0">File not found or could not be displayed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const careerModalEl = document.getElementById('careerPreviewModal');
            const careerModal = new bootstrap.Modal(careerModalEl);
            const spinner = document.getElementById('careerSpinner');
            const img = document.getElementById('careerPreviewImage');
            const errorMsg = document.getElementById('careerPreviewError');

            // Button preview logic
            document.querySelectorAll('.career-preview-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const previewUrl = this.dataset.previewUrl;

                    img.style.display = 'none';
                    errorMsg.style.display = 'none';
                    spinner.style.display = 'block';

                    careerModal.show();

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
            careerModalEl.addEventListener('hidden.bs.modal', function() {
                img.src = '';
                img.style.display = 'none';
                errorMsg.style.display = 'none';
                spinner.style.display = 'none';
            });
        });
    </script>

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
