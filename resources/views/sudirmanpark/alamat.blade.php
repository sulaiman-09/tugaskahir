@extends('layouts.app')

@section('title', 'Sudirman Park - Alamat Homepass')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 sudirmanpark-page">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 mb-3">
            {{-- Header Card --}}
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                {{-- Judul --}}
                <h3 class="fw-bold mb-0 text-dark">Kelola Alamat Homepass - Sudirman Park</h3>

                {{-- Toolbar --}}
                <div class="d-flex align-items-center gap-2 sudirman-toolbar">
                    {{-- Tombol Back --}}
                    <a href="{{ route('sudirmanpark.index') }}"
                        class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center toolbar-item"
                        style="width: 36px; height: 36px; padding: 6px 8px; border-radius: 6px;">
                        <i class="bi bi-chevron-left" style="font-size: 1rem;"></i>
                    </a>

                    {{-- Export Dropdown --}}
                    <div class="btn-group toolbar-item">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center"
                            type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; width: 36px; height: 36px; padding: 6px 8px; border-radius: 6px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('sudirmanpark.exportHomepassExcel') }}">Export
                                    Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('sudirmanpark.exportHomepassPdf') }}">Export PDF</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Tambah Alamat --}}
                    <a href="{{ route('sudirmanpark.createHomepass') }}"
                        class="btn btn-sm d-flex align-items-center justify-content-center toolbar-item"
                        style="background-color: #000; border: 1px solid #000; color: #fff; width: 36px; height: 36px; padding: 6px 8px; border-radius: 6px;">
                        <i class="bi bi-building-add" style="color: #fff; font-size: 1rem;"></i>
                    </a>

                    {{-- Delete Selected --}}
                    <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn toolbar-item"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                    </button>

                    {{-- Search --}}
                    <form action="{{ route('sudirmanpark.alamat') }}" method="GET"
                        class="d-flex align-items-center ms-auto toolbar-search" style="max-width: 420px; width:100%;">
                        <input type="text" name="q" class="form-control form-control-sm"
                            placeholder="Search tower, floor, or unit" value="{{ $q ?? request('q') }}">
                        <input type="hidden" name="show_all" value="{{ $showAll ? '1' : '0' }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th style="width: 40px;">
                                    <input type="checkbox" id="checkAll">
                                </th>
                                <th>Tower</th>
                                <th>Floor</th>
                                <th>Unit</th>
                                <th>Full Address</th>
                                <th>Total Customers</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th style="width: 110px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addresses as $index => $address)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="rowCheckbox" value="{{ $address->id }}">
                                    </td>
                                    <td>{{ $address->tower }}</td>
                                    <td>{{ $address->floor }}</td>
                                    <td>{{ $address->unit }}</td>
                                    <td class="text-start ps-3">{{ $address->full_address }}</td>
                                    <td>{{ $address->jumlah_customer ?? 0 }}</td>
                                    <td>
                                        <span class="badge {{ $address->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $address->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $address->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('sudirmanpark.editHomepass', $address->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('sudirmanpark.destroyHomepass', $address->id) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted text-center py-4">No address data found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        {{-- Per Page Dropdown --}}
        <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-3 flex-wrap">

            {{-- Left: Per Page + Showing --}}
            <div class="left-info d-flex align-items-center flex-wrap gap-2">

                {{-- Per Page Dropdown --}}
                <form method="GET" action="{{ route('sudirmanpark.alamat') }}" id="perPageForm"
                    class="d-flex align-items-center gap-2">

                    <label for="per_page" class="mb-0 small text-muted">Show</label>

                    <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100, 'All'] as $size)
                            <option value="{{ $size }}"
                                {{ strtolower(request('per_page', 10)) == strtolower($size) ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Pertahankan query lain --}}
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                </form>

                {{-- Showing Text --}}
                <div class="small text-muted">
                    Showing {{ $addresses->firstItem() }} to {{ $addresses->lastItem() }}
                    of {{ $addresses->total() }} results
                </div>

            </div>

            {{-- Right: Pagination --}}
            <div class="right-pagination pagination-sm">
                {{ $addresses->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Modal: Create / Edit Homepass -->
        <div class="modal fade" id="homepassModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="homepassModalLabel">+ Add Homepass</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="homepassForm">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="hp_id">
                            <div class="mb-3">
                                <label class="form-label">Tower</label>
                                <input type="text" name="tower" id="hp_tower" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Floor</label>
                                <input type="text" name="floor" id="hp_floor" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <input type="text" name="unit" id="hp_unit" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="hp_status" class="form-select">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="hpSaveBtn">Create Homepass</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
            <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="liveToastBody">Action completed</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/sudirmanpark.css') }}">
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Konfirmasi hapus
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', e => {
                        e.preventDefault();
                        const name = form.dataset.name || 'record ini';
                        if (confirm(
                                `Yakin ingin menghapus ${name}? Aksi ini tidak dapat dibatalkan.`)) {
                            form.submit();
                        }
                    });
                });

                // Homepass modal handlers
                const homepassModalEl = document.getElementById('homepassModal');
                const homepassModal = new bootstrap.Modal(homepassModalEl);
                const homepassForm = document.getElementById('homepassForm');
                const hpSaveBtn = document.getElementById('hpSaveBtn');

                // Open create modal
                document.querySelectorAll('a[href$="createHomepass"]').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        document.getElementById('homepassModalLabel').textContent = 'Tambah Homepass';
                        homepassForm.reset();
                        document.getElementById('hp_id').value = '';
                        homepassModal.show();
                    });
                });

                // Open edit modal from edit button
                document.querySelectorAll('a[href*="/homepass/"]').forEach(link => {
                    if (link.href.match(/\/homepass\/\d+\/edit$/)) {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetch(link.href)
                                .then(res => res.text())
                                .then(html => {
                                    // parse simple values from returned HTML (view editHomepass contains inputs with values)
                                    const tmp = document.createElement('div');
                                    tmp.innerHTML = html;
                                    const tower = tmp.querySelector('input[name="tower"]').value;
                                    const floor = tmp.querySelector('input[name="floor"]').value;
                                    const unit = tmp.querySelector('input[name="unit"]').value;
                                    const status = tmp.querySelector('select[name="status"]').value;
                                    const idMatch = link.href.match(/homepass\/(\d+)\/edit$/);
                                    const id = idMatch ? idMatch[1] : '';
                                    document.getElementById('hp_id').value = id;
                                    document.getElementById('hp_tower').value = tower;
                                    document.getElementById('hp_floor').value = floor;
                                    document.getElementById('hp_unit').value = unit;
                                    document.getElementById('hp_status').value = status;
                                    document.getElementById('homepassModalLabel').textContent =
                                        'Edit Homepass';
                                    homepassModal.show();
                                });
                        });
                    }
                });

                // Submit create/edit via AJAX
                homepassForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    hpSaveBtn.disabled = true;
                    const id = document.getElementById('hp_id').value;
                    // Use FormData and POST with _method override when updating to avoid blocked HTTP verbs
                    const formData = new FormData();
                    formData.append('tower', document.getElementById('hp_tower').value);
                    formData.append('floor', document.getElementById('hp_floor').value);
                    formData.append('unit', document.getElementById('hp_unit').value);
                    formData.append('status', document.getElementById('hp_status').value);
                    formData.append('_token', '{{ csrf_token() }}');

                    let url = `{{ route('sudirmanpark.storeHomepass') }}`;
                    if (id) {
                        url = `/sudirmanpark/homepass/${id}`;
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData,
                        })
                        .then(async res => {
                            hpSaveBtn.disabled = false;
                            if (!res.ok) {
                                // try to parse error message
                                let msg = 'Gagal menyimpan homepass';
                                try {
                                    const errJson = await res.json();
                                    if (errJson && errJson.message) msg = errJson.message;
                                } catch (e) {
                                    // ignore parse error
                                }
                                alert(msg);
                                return;
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (!data) return;
                            homepassModal.hide();
                            showToast(data.success ? 'Berhasil disimpan' : 'Gagal');
                            setTimeout(() => location.reload(), 600);
                        })
                        .catch(err => {
                            hpSaveBtn.disabled = false;
                            alert('Error saving homepass');
                            console.error(err);
                        });
                });

                // Delete homepass via AJAX
                document.querySelectorAll('form[action*="homepass"]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (!confirm('Yakin ingin menghapus alamat ini?')) return;
                        const action = form.action;
                        // send as POST with _method=DELETE for compatibility
                        const delData = new FormData();
                        delData.append('_token', '{{ csrf_token() }}');
                        delData.append('_method', 'DELETE');
                        fetch(action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: delData
                            })
                            .then(async res => {
                                if (!res.ok) {
                                    let msg = 'Gagal menghapus';
                                    try {
                                        const ej = await res.json();
                                        if (ej && ej.message) msg = ej.message;
                                    } catch (e) {}
                                    alert(msg);
                                    return;
                                }
                                return res.json();
                            })
                            .then(data => {
                                if (!data) return;
                                showToast('Berhasil dihapus');
                                form.closest('tr').remove();
                            })
                            .catch(() => alert('Gagal menghapus'));
                    });
                });

                // KTP delete via AJAX in edit page
                document.querySelectorAll('form[action$="/ktp"]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (!confirm('Hapus file KTP?')) return;
                        // use POST + _method override for delete to avoid server blocking DELETE
                        const delKtp = new FormData();
                        delKtp.append('_token', '{{ csrf_token() }}');
                        delKtp.append('_method', 'DELETE');
                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: delKtp
                            })
                            .then(res => res.json())
                            .then(data => {
                                showToast('KTP dihapus');
                                location.reload();
                            })
                            .catch(() => alert('Gagal menghapus KTP'));
                    });
                });

                function showToast(message) {
                    const toastEl = document.getElementById('liveToast');
                    document.getElementById('liveToastBody').textContent = message;
                    const t = new bootstrap.Toast(toastEl);
                    t.show();
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const checkAll = document.getElementById('checkAll');
                const rowCheckboxes = document.querySelectorAll('.rowCheckbox');

                // Select / deselect all
                if (checkAll) {
                    checkAll.addEventListener('change', () => {
                        rowCheckboxes.forEach(cb => cb.checked = checkAll.checked);
                    });
                }

                // Tombol Bulk Delete
                const bulkDeleteBtn = document.createElement('button');
                bulkDeleteBtn.addEventListener('click', () => {
                    const ids = Array.from(rowCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    if (ids.length === 0) {
                        alert('Pilih minimal satu alamat.');
                        return;
                    }

                    if (!confirm(`Yakin ingin menghapus ${ids.length} alamat terpilih?`)) return;

                    fetch('{{ route('sudirmanpark.bulkDeleteHomepass') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ids
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                            if (data.success) location.reload();
                        })
                        .catch(() => alert('Terjadi kesalahan.'));
                });

                // Tambahkan tombol ke card body (misal setelah tombol tambah homepass)
                const cardBody = document.querySelector('.card-body.d-flex.flex-wrap');
                if (cardBody) cardBody.appendChild(bulkDeleteBtn);
            });
        </script>
    @endpush
