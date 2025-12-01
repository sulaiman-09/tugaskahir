@extends('layouts.app')

@section('title', 'Data Division')

@section('content')
    <div class="container py-4 division-page">


        {{-- Card utama --}}
        <div class="card border-0 shadow-sm rounded-3">

<div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <!-- Judul kiri -->
    <h3 class="fw-bold mb-0">Data Division</h3>

    <!-- Toolbar kanan -->
    <div class="d-flex align-items-center gap-2 justify-content-end flex-grow-1">
        <!-- Export Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center"
                type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('division.export.excel') }}">Export Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('division.export.pdf') }}">Export PDF</a></li>
            </ul>
        </div>

        <!-- Add Division -->
        <a href="{{ route('division.create') }}"
            class="btn btn-sm d-flex align-items-center justify-content-center"
            style="background-color: #000; border: 1px solid #000; color: #fff; width: 36px; height: 36px; padding: 6px 8px; position: relative;">
            <!-- Icon utama: division -->
            <i class="bi bi-diagram-3" style="color: #fff; font-size: 1rem;"></i>
            <!-- Overlay plus -->
            <i class="bi bi-plus-lg"
                style="color: #fff; font-size: 0.7rem; position: absolute; top: 0; right: 0;"></i>
        </a>

        <!-- Delete Selected -->
        <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn"
            style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
            <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
        </button>

        <!-- Search Form -->
        <form action="{{ route('division.index') }}" method="GET" class="d-flex align-items-center flex-shrink-0"
            style="min-width: 260px; max-width: 400px;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name..."
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
                    <table class="table align-middle mb-0 text-center table-striped table-hover">
                        <thead style="background-color: #e7f0ff; color: #003366; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-center">
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Customer Leads</th>
                                <th>Created At</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($divisions as $division)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="select-item" value="{{ $division->id }}">
                                    </td>
                                    <td>{{ $division->id }}</td>
                                    <td class="text-start ps-3">{{ $division->name }}</td>
                                    <td class="text-start">{{ $division->description }}</td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status-toggle" type="checkbox"
                                                data-id="{{ $division->id }}" {{ $division->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ $division->customer_leads ?? 0 }}</td>
                                    <td>{{ $division->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-warning btn-sm edit-division-btn"
                                                data-url="{{ route('division.edit', $division->id) }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('division.destroy', $division->id) }}" method="POST"
                                                class="delete-form" data-name="{{ $division->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted text-center py-4">No division data available.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- Footer: per page + showing + pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-3">

                {{-- Left side: Show per page + Showing text --}}
                <div class="d-flex align-items-center flex-wrap gap-3">

                    {{-- Records per page --}}
                    <form method="GET" action="{{ route('division.index') }}" id="perPageForm"
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

                        {{-- Keep other filters --}}
                        @foreach (request()->except('per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>

                    {{-- Showing text DI SAMPING Show Per Page --}}
                    <div class="small text-muted">
                        @if ($divisions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Showing {{ $divisions->firstItem() ?? 0 }} to {{ $divisions->lastItem() ?? 0 }}
                            of {{ $divisions->total() }} Results
                        @else
                            Showing 1 to {{ $divisions->count() }} of {{ $divisions->count() }} Results
                        @endif
                    </div>

                </div>

                {{-- Right side: Pagination --}}
                <div class="right-pagination pagination-sm">
                    @if ($divisions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $divisions->links() }}
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Modal edit Division --}}
    <div class="modal fade" id="editDivisionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDivisionModalTitle">Edit Division</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editDivisionModalBody">
                    <div class="text-center py-4">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal edit Division (AJAX)
            const editModalEl = document.getElementById('editDivisionModal');
            const editModalBody = document.getElementById('editDivisionModalBody');
            const editModalTitle = document.getElementById('editDivisionModalTitle');
            const editModal = new bootstrap.Modal(editModalEl);

            function wireDivisionForm(container) {
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

            function loadDivisionForm(url) {
                editModalTitle.textContent = 'Edit Division';
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
                        wireDivisionForm(editModalBody);
                    })
                    .catch(() => {
                        editModalBody.innerHTML = '<div class="text-danger">Gagal memuat form.</div>';
                    });
            }

            document.querySelectorAll('.edit-division-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    loadDivisionForm(btn.dataset.url);
                });
            });

            // Toggle status
            document.querySelectorAll('.status-toggle').forEach(cb => {
                cb.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const status = this.checked ? 1 : 0;

                    fetch(`/division/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status
                        })
                    }).then(res => res.json()).then(data => {
                        if (!data.success) alert('Update status gagal.');
                    }).catch(() => alert('Error koneksi.'));
                });
            });

            // Konfirmasi hapus
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    const name = form.dataset.name || 'this record';
                    if (confirm(`Are you sure you want to delete ${name}?`)) form.submit();
                });
            });
        });

        // Select / deselect all
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.select-item');

        selectAll?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Delete selected
        document.getElementById('deleteSelected')?.addEventListener('click', function() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one division to delete.');
                return;
            }

            if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected division(s)?`)) return;

            fetch('{{ route('division.bulkDelete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert(data.message || 'Failed to delete.');
                })
                .catch(() => alert('Error connecting to server.'));
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/division.css') }}">
@endpush
