@extends('layouts.app')

@section('title', 'Career Management')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 career-page">


        {{-- Card Utama --}}
        <div class="card border-0 shadow-sm rounded-3">
            {{-- Header Card --}}
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                <!-- Judul di kiri -->
                <h3 class="fw-bold mb-0">Career Management</h3>

                <!-- Toolbar di kanan -->
                <div class="d-flex gap-2 align-items-center toolbar-scroll career-toolbar">
                    <!-- Export Dropdown -->
                    <div class="dropdown toolbar-item">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center" type="button"
                            id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('career.export.excel') }}">Export Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('career.export.pdf') }}">Export PDF</a></li>
                        </ul>
                    </div>

                    <!-- Add Career -->
                    <a href="{{ route('career.create') }}"
                        class="btn btn-sm d-flex align-items-center justify-content-center toolbar-item"
                        style="background-color: #000; border: 1px solid #000; color: #fff; width: 36px; height: 36px; padding: 6px 8px; position: relative;">
                        <i class="bi bi-briefcase" style="color: #fff; font-size: 1rem;"></i>
                        <i class="bi bi-plus-lg"
                            style="color: #fff; font-size: 0.7rem; position: absolute; top: 2px; right: 2px;"></i>
                    </a>

                    <!-- Delete Selected -->
                    <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn toolbar-item"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                    </button>

                    <!-- Search Form -->
                    <form action="{{ route('career.index') }}" method="GET" class="d-flex align-items-center ms-2 toolbar-search"
                        style="max-width:360px; width:100%">
                        <div class="input-group input-group-sm w-100">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search title, type, or location..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            {{-- Tabel --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th><input type="checkbox" id="selectAll"></th>
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
                                    <td><input type="checkbox" name="ids[]" class="career-checkbox"
                                            value="{{ $career->id }}"></td>
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
                                            class="badge {{ $career->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $career->status }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($career->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-warning btn-sm edit-career-btn"
                                                data-url="{{ route('career.edit', $career->id) }}" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
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
                                    <td colspan="10" class="text-muted text-center py-4">No career data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer: per page + showing + pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-3">

                {{-- Left: Show per page + showing --}}
                <div class="d-flex align-items-center flex-wrap gap-3">

                    <form method="GET" action="{{ route('career.index') }}" id="perPageForm"
                        class="d-flex align-items-center gap-2">
                        <label for="per_page" class="mb-0 small text-muted">Show</label>

                        <select name="per_page" id="per_page" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'All'] as $size)
                                <option value="{{ $size }}"
                                    {{ strtolower(request('per_page', 15)) == strtolower($size) ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Pertahankan filter lain --}}
                        @foreach (request()->except('per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>

                    {{-- Showing text --}}
                    <div class="small text-muted">
                        @if ($careers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Showing {{ $careers->firstItem() ?? 0 }} to {{ $careers->lastItem() ?? 0 }}
                            of {{ $careers->total() }} Results
                        @else
                            Showing 1 to {{ $careers->count() }} of {{ $careers->count() }} Results
                        @endif
                    </div>

                </div>

                {{-- Right: Pagination --}}
                <div class="right-pagination pagination-sm">
                    @if ($careers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $careers->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    @endif
                </div>

            </div>

        </div>
    </div>

    {{-- Modal edit Career --}}
    <div class="modal fade" id="editCareerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCareerModalTitle">Edit Career</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editCareerModalBody">
                    <div class="text-center py-4">Loading...</div>
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
                    Data berhasil dihapus!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal edit Career (AJAX)
            const editModalEl = document.getElementById('editCareerModal');
            const editModalBody = document.getElementById('editCareerModalBody');
            const editModalTitle = document.getElementById('editCareerModalTitle');
            const editModal = new bootstrap.Modal(editModalEl);

            function wireCareerForm(container) {
                const form = container.querySelector('form');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;

                    const errorBox = form.querySelector('[data-error-box]');
                    if (errorBox) {
                        errorBox.classList.add('d-none');
                        errorBox.innerHTML = '';
                    }

                    fetch(form.action, {
                            method: form.method || 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: new FormData(form)
                        })
                        .then(async res => {
                            if (submitBtn) submitBtn.disabled = false;

                            if (res.ok) {
                                editModal.hide();
                                window.location.reload();
                                return;
                            }

                            if (res.status === 422) {
                                const data = await res.json();
                                const messages = Object.values(data.errors || {}).flat();
                                if (errorBox) {
                                    errorBox.classList.remove('d-none');
                                    errorBox.innerHTML = messages.map(m => `<div>${m}</div>`).join('');
                                }
                                return;
                            }

                            alert('Gagal menyimpan data. Silakan coba lagi.');
                        })
                        .catch(() => {
                            if (submitBtn) submitBtn.disabled = false;
                            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                        });
                });
            }

            function loadCareerForm(url) {
                editModalTitle.textContent = 'Edit Career';
                editModalBody.innerHTML = '<div class="text-center py-4">Loading...</div>';
                editModal.show();

                fetch(url + (url.includes('?') ? '&' : '?') + 'modal=1', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        editModalBody.innerHTML = html;
                        wireCareerForm(editModalBody);
                    })
                    .catch(() => {
                        editModalBody.innerHTML = '<div class="text-danger">Gagal memuat form.</div>';
                    });
            }

            document.querySelectorAll('.edit-career-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    loadCareerForm(btn.dataset.url);
                });
            });

            // Modal preview
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
    </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const careerModalEl = document.getElementById('careerPreviewModal');
            const careerModal = new bootstrap.Modal(careerModalEl);
            const spinner = document.getElementById('careerSpinner');
            const img = document.getElementById('careerPreviewImage');
            const errorMsg = document.getElementById('careerPreviewError');

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

            careerModalEl.addEventListener('hidden.bs.modal', function() {
                img.src = '';
                img.style.display = 'none';
                errorMsg.style.display = 'none';
                spinner.style.display = 'none';
            });

            // Checkbox select all
            document.getElementById('selectAll').addEventListener('change', function() {
                document.querySelectorAll('.career-checkbox').forEach(cb => cb.checked = this.checked);
            });

            // Delete selected
            document.getElementById('deleteSelected').addEventListener('click', function() {
                const selected = Array.from(document.querySelectorAll('.career-checkbox:checked')).map(cb =>
                    cb.value);
                if (selected.length === 0) return alert('Pilih minimal satu data untuk dihapus.');
                if (!confirm(`Yakin ingin menghapus ${selected.length} data terpilih?`)) return;

                fetch("{{ route('career.bulkDelete') }}", {
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
                            // Tampilkan toast
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
        });
    </script>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/career.css') }}">
    @endpush

@endsection
