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
                <h3 class="fw-bold mb-0 text-dark">Kelola Alamat Homepass</h3>

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

                    {{-- Tambah Alamat (halaman baru) --}}
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
                                            <button type="button" class="btn btn-warning btn-sm edit-homepass-btn"
                                                data-id="{{ $address->id }}"
                                                data-tower="{{ $address->tower }}"
                                                data-floor="{{ $address->floor }}"
                                                data-unit="{{ $address->unit }}"
                                                data-status="{{ $address->is_active ? 'Active' : 'Inactive' }}"
                                                data-update-url="{{ route('sudirmanpark.updateHomepass', $address->id) }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
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
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header sticky-top bg-white" style="z-index: 2; border-bottom: 1px solid #f1f5f9;">
                        <h5 class="modal-title" id="homepassModalLabel">+ Add Homepass</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="homepassForm" method="POST" action="{{ route('sudirmanpark.storeHomepass') }}">
                        @csrf
                        <input type="hidden" name="_method" id="hp_method" value="POST">
                        <div class="modal-body" style="max-height: 65vh; overflow-y: auto; padding-top: 1rem;">
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
                // Modal setup for create/edit
                const homepassModalEl = document.getElementById('homepassModal');
                const homepassModal = new bootstrap.Modal(homepassModalEl);
                const homepassForm = document.getElementById('homepassForm');
                const hpId = document.getElementById('hp_id');
                const hpMethod = document.getElementById('hp_method');
                const hpTower = document.getElementById('hp_tower');
                const hpFloor = document.getElementById('hp_floor');
                const hpUnit = document.getElementById('hp_unit');
                const hpStatus = document.getElementById('hp_status');
                const hpSaveBtn = document.getElementById('hpSaveBtn');
                const modalLabel = document.getElementById('homepassModalLabel');

                function setEditMode(btn) {
                    homepassForm.action = btn.dataset.updateUrl;
                    hpMethod.value = 'PUT';
                    hpId.value = btn.dataset.id || '';
                    hpTower.value = (btn.dataset.tower || '').toUpperCase();
                    hpFloor.value = (btn.dataset.floor || '').toUpperCase();
                    hpUnit.value = (btn.dataset.unit || '').toUpperCase();
                    hpStatus.value = btn.dataset.status || 'Active';
                    hpSaveBtn.textContent = 'Update Homepass';
                    modalLabel.textContent = 'Edit Homepass';
                }

                document.querySelectorAll('.edit-homepass-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        setEditMode(btn);
                        homepassModal.show();
                    });
                });

                // uppercase on input
                [hpTower, hpFloor, hpUnit].forEach(el => {
                    el.addEventListener('input', () => {
                        el.value = el.value.toUpperCase();
                    });
                });

                homepassForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    hpSaveBtn.disabled = true;
                    const formData = new FormData(homepassForm);

                    if (hpMethod.value === 'PUT') {
                        formData.set('_method', 'PUT');
                    } else {
                        formData.delete('_method');
                    }

                    fetch(homepassForm.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async res => {
                            hpSaveBtn.disabled = false;
                            if (!res.ok) {
                                let msg = 'Gagal menyimpan homepass';
                                try {
                                    const errJson = await res.json();
                                    if (errJson && errJson.message) msg = errJson.message;
                                } catch (err) {}
                                alert(msg);
                                return null;
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (!data) return;
                            homepassModal.hide();
                            showToast(data.message || 'Berhasil disimpan');
                            setTimeout(() => location.reload(), 500);
                        })
                        .catch(err => {
                            console.error(err);
                            hpSaveBtn.disabled = false;
                            alert('Terjadi kesalahan saat menyimpan.');
                        });
                });

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

                // Delete homepass via AJAX
                document.querySelectorAll('form.delete-form').forEach(form => {
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
                                // Pastikan kembali ke daftar untuk menghindari 404 setelah delete
                                setTimeout(() => {
                                    window.location.href = "{{ route('sudirmanpark.alamat') }}";
                                }, 300);
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
