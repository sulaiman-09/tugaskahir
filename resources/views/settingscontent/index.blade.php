@extends('layouts.app')

@section('title', 'Settings Content')

@section('content')
    <div class="container py-4">

        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                <!-- Judul kiri -->
                <h3 class="fw-bold mb-0 text-dark">Settings Content</h3>

                <!-- Toolbar kanan -->
                <div class="d-flex align-items-center gap-2 justify-content-end flex-grow-1">
                    <!-- Export Dropdown -->
                    <div class="btn-group">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center" type="button"
                            id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px; border-radius: 6px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('settings-content.export.excel') }}">Export
                                    Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings-content.export.pdf') }}">Export PDF</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Add Content -->
                    <a href="{{ route('settings-content.create') }}"
                        class="btn btn-sm d-flex align-items-center justify-content-center"
                        style="background-color: #000; border: 1px solid #000; color: #fff; width: 36px; height: 36px; padding: 6px 8px; position: relative; border-radius: 6px;">
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
                    <div class="d-flex align-items-center" style="min-width: 260px; max-width: 400px;">
                        <form action="{{ route('settings-content.index') }}" method="GET" class="d-flex w-100">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search title or name" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm ms-2">
                                <i class="fa fa-search"></i>
                            </button>

                            <!-- Pertahankan per_page -->
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        </form>
                    </div>
                </div>
            </div>


            {{-- Alert sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 700;">
                            <tr class="text-dark">
                                <th><input type="checkbox" id="selectAllContents"></th> {{-- Checkbox select all --}}
                                <th>No</th>
                                <th>Title</th>
                                <th>Name</th>
                                <th>Type ID</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Image</th>
                                <th>Icon</th>
                                <th style="width: 150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contents as $index => $content)
                                <tr>
                                    <td><input type="checkbox" class="content-checkbox" value="{{ $content->id }}"></td>
                                    <td>{{ $contents->firstItem() + $index }}</td>
                                    <td class="text-start ps-3">{{ $content->title }}</td>
                                    <td>{{ $content->name }}</td>
                                    <td>{{ $content->content_type_id }}</td>
                                    <td>{{ $content->order }}</td>
                                    <td>
                                        @if ($content->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($content->image_path)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary content-preview-btn"
                                                data-preview-url="{{ asset('storage/' . $content->image_path) }}">
                                                View Image
                                            </button>
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($content->icon_path)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary content-preview-btn"
                                                data-preview-url="{{ asset('storage/' . $content->icon_path) }}">
                                                View Icon
                                            </button>
                                        @else
                                            <span class="text-muted">No Icon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-warning btn-sm edit-content-btn"
                                                data-url="{{ route('settings-content.edit', $content->id) }}"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('settings-content.destroy', $content->id) }}"
                                                method="POST" class="delete-form" data-name="{{ $content->title }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">No data found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                        <!-- Modal Preview Content -->
                        <div class="modal fade" id="contentPreviewModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="contentPreviewTitle">Preview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body text-center">
                                        <img id="contentPreviewImage" src="" class="img-fluid rounded shadow"
                                            alt="Preview">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </table>
                </div>
            </div>

            {{-- Footer: Records per page & Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-2">

                {{-- Left: Show per page + Showing results --}}
                <div class="d-flex align-items-center gap-3 flex-wrap">

                    {{-- Show per page --}}
                    <form method="GET" action="{{ route('settings-content.index') }}" id="perPageForm"
                        class="d-flex align-items-center gap-2">
                        <label for="per_page" class="mb-0">Show</label>

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

                    {{-- Showing results (sejajar dengan dropdown) --}}
                    <small class="text-muted">
                        Showing {{ $contents->firstItem() ?? 0 }} to {{ $contents->lastItem() ?? 0 }}
                        of {{ $contents->total() }} Results
                    </small>

                </div>

                {{-- Right: Pagination --}}
                <div>
                    {{ $contents->links() }}
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modal edit Settings Content --}}
    <div class="modal fade" id="editContentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContentModalTitle">Edit Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editContentModalBody">
                    <div class="text-center py-4">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal edit Content (AJAX)
                const editModalEl = document.getElementById('editContentModal');
                const editModalBody = document.getElementById('editContentModalBody');
                const editModalTitle = document.getElementById('editContentModalTitle');
                const editModal = new bootstrap.Modal(editModalEl);

                function wireContentForm(container) {
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

                function loadContentForm(url) {
                    editModalTitle.textContent = 'Edit Content';
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
                            wireContentForm(editModalBody);
                        })
                        .catch(() => {
                            editModalBody.innerHTML = '<div class="text-danger">Gagal memuat form.</div>';
                        });
                }

                document.querySelectorAll('.edit-content-btn').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.preventDefault();
                        loadContentForm(btn.dataset.url);
                    });
                });

                // Konfirmasi hapus
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const name = form.dataset.name || 'this record';
                        if (confirm(
                                `Are you sure you want to delete "${name}"? This action cannot be undone.`
                            )) {
                            form.submit();
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Select all checkbox
                document.getElementById('selectAllContents').addEventListener('change', function() {
                    document.querySelectorAll('.content-checkbox').forEach(cb => cb.checked = this.checked);
                });

                // Bulk delete
                document.getElementById('deleteSelectedContents').addEventListener('click', () => {
                    const selected = Array.from(document.querySelectorAll('.content-checkbox:checked')).map(
                        cb => cb.value);
                    if (selected.length === 0) return alert('Pilih minimal satu content untuk dihapus.');
                    if (!confirm(`Yakin ingin menghapus ${selected.length} content terpilih?`)) return;

                    fetch("{{ route('settings-content.bulkDelete') }}", {
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
                                alert(data.message);
                                location.reload();
                            } else alert(data.message);
                        })
                        .catch(() => alert('Terjadi kesalahan.'));
                });
            });
        </script>

        <script>
            document.addEventListener("click", function(e) {
                const btn = e.target.closest(".content-preview-btn");
                if (!btn) return;

                // Ambil URL preview
                const fileUrl = btn.dataset.previewUrl;

                // Set gambar ke dalam modal
                document.getElementById("contentPreviewImage").src = fileUrl;

                // Tampilkan modal
                const modal = new bootstrap.Modal(document.getElementById("contentPreviewModal"));
                modal.show();
            });
        </script>
    @endpush
@endsection
