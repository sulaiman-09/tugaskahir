@extends('layouts.app')

@section('title', 'Sudirman Park - Customer Management')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 sudirmanpark-page">

        {{-- Tombol Aksi --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2">
                {{-- Judul --}}
                <div class="d-flex flex-column">
                    <h3 class="fw-bold mb-0 text-dark">Sudirman Park - </h3>
                    <h3 class="fw-semibold mb-0 text-dark">Customer Management</h3>
                </div>

                {{-- Toolbar --}}
                <div class="d-flex gap-2 align-items-center toolbar-scroll sudirman-toolbar">
                    {{-- Export --}}
                    <div class="dropdown toolbar-item">
                        <button class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center" type="button"
                            id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: white; border: 1px solid #000; color: #000; padding: 6px 8px; width: 36px; height: 36px;">
                            <i class="fa fa-print" style="color: #000; font-size: 1rem;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('sudirmanpark.exportHomepassExcel') }}">
                                    Export Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('sudirmanpark.exportHomepassPdf') }}">
                                    Export PDF
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Tambah Customer --}}
                    <a href="{{ route('sudirmanpark.create') }}"
                        class="btn btn-sm toolbar-btn d-flex align-items-center justify-content-center toolbar-item"
                        style="background-color: #000; border: 1px solid #000; color: #fff; width: 36px; height: 36px; padding: 6px 8px;">
                        <i class="bi bi-person-plus" style="color: #fff; font-size: 1rem;"></i>
                    </a>

                    {{-- Manage Links --}}
                    <a href="{{ route('sudirmanpark.alamat') }}"
                        class="btn btn-sm toolbar-item {{ request()->routeIs('sudirmanpark.alamat') ? 'toolbar-btn toolbar-btn-primary' : 'toolbar-btn toolbar-btn-ghost' }}"
                        style="{{ request()->routeIs('sudirmanpark.alamat') ? '' : 'background-color: white; border: 1px solid #000; color: #000;' }}">
                        Manage Tower Address
                    </a>

                    <a href="{{ route('product.index') }}"
                        class="btn btn-sm toolbar-item {{ request()->routeIs('product.*') ? 'toolbar-btn toolbar-btn-primary' : 'toolbar-btn toolbar-btn-ghost' }}"
                        style="{{ request()->routeIs('product.*') ? '' : 'background-color: white; border: 1px solid #000; color: #000;' }}">
                        Manage Product
                    </a>

                    {{-- Delete Selected --}}
                    <button type="button" id="deleteSelected" class="btn btn-sm toolbar-btn toolbar-item"
                        style="background-color: white; border: 1px solid #dc3545; color: #dc3545;">
                        <i class="fa fa-trash me-1" style="color: #dc3545;"></i> Delete Selected
                    </button>

                    {{-- Search --}}
                    <form action="{{ route('sudirmanpark.index') }}" method="GET"
                        class="ms-auto d-flex align-items-center toolbar-search" style="max-width:420px; width:100%;">
                        <div class="input-group input-group-sm w-100">
                            <input type="text" name="q" class="form-control form-control-sm"
                                placeholder="Search name, phone or email" value="{{ $q ?? request('q') }}">
                            <button class="btn btn-primary btn-sm" type="submit" title="Search">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <input type="hidden" name="show_all" value="{{ $showAll ? '1' : '0' }}">
                    </form>
                </div>
            </div>

            {{-- Card Tabel --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless"
                            style="white-space: nowrap; width: max-content; min-width: 100%;">

                            <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <tr class="fw-semibold text-dark">
                                    <th><input type="checkbox" id="selectAllCustomers"></th>
                                    <th style="width: 40px;">No</th>
                                    <th>Customer Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Tower Address</th>
                                    <th>Package</th>
                                    <th>ID Card</th>
                                    <th>Status</th>
                                    <th>Change Status</th>
                                    <th>Status Update Info</th>
                                    <th>Created At</th>
                                    <th style="width: 110px;">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($customers as $index => $customer)
                                    <tr>
                                        <td><input type="checkbox" class="customer-checkbox" value="{{ $customer->id }}">
                                        </td>

                                        <td>{{ $customers->firstItem() + $index }}</td>

                                        <td class="text-start ps-3">{{ $customer->name }}</td>

                                        <td>{{ $customer->phone }}</td>

                                        <td>{{ $customer->email }}</td>

                                        <td>{{ $customer->tower }}</td>

                                        <td>{{ $customer->package }}</td>

                                        <td>
                                            @if ($customer->ktp)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary ktp-preview-btn"
                                                    data-preview-url="{{ route('sudirmanpark.previewKtp', $customer->id) }}"
                                                    data-ktp="{{ $customer->ktp }}">
                                                    View
                                                </button>
                                            @else
                                                <span class="text-muted">No File</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span
                                                class="badge
                                @if ($customer->status == 'approved') bg-success
                                @elseif ($customer->status == 'processed') bg-warning
                                @elseif ($customer->status == 'registration') bg-info
                                @elseif ($customer->status == 'cancelled') bg-danger @endif">
                                                {{ ucfirst($customer->status) }}
                                            </span>
                                        </td>

                                        <td>
                                            <select class="form-select form-select-sm status-change"
                                                data-id="{{ $customer->id }}">
                                                <option value="registration"
                                                    {{ $customer->status == 'registration' ? 'selected' : '' }}>
                                                    Registration
                                                </option>
                                                <option value="processed"
                                                    {{ $customer->status == 'processed' ? 'selected' : '' }}>
                                                    Processed
                                                </option>
                                                <option value="approved"
                                                    {{ $customer->status == 'approved' ? 'selected' : '' }}>
                                                    Approved
                                                </option>
                                                <option value="cancelled"
                                                    {{ $customer->status == 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled
                                                </option>
                                            </select>
                                        </td>

                                        <td>{{ $customer->status_change ?? '-' }}</td>

                                        <td>{{ $customer->created_at->format('d-m-Y') }}</td>

                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-warning btn-sm btn-edit-ajax"
                                                    title="Edit" data-id="{{ $customer->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="{{ route('sudirmanpark.destroy', $customer->id) }}"
                                                    method="POST" class="delete-form" data-name="{{ $customer->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="13" class="text-muted text-center py-4">
                                            No customer data available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-3 flex-wrap">

                {{-- Left: Per Page + Showing --}}
                <div class="left-info d-flex align-items-center flex-wrap gap-3">

                    {{-- Per Page --}}
                    <form method="GET" action="{{ route('sudirmanpark.index') }}" id="perPageForm"
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

                        {{-- Pertahankan query lain --}}
                        @foreach (request()->except('per_page', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>

                    {{-- Showing Text --}}
                    <div class="small text-muted">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }}
                        of {{ $customers->total() }} results
                    </div>
                </div>

                {{-- Right: Pagination --}}
                <div class="right-pagination">
                    @if ($customers->hasPages())
                        {{ $customers->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>

        @endsection

        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/sudirmanpark.css') }}">
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Select all
                    document.getElementById('selectAllCustomers').addEventListener('change', function() {
                        document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = this.checked);
                    });

                    // Bulk delete
                    document.getElementById('deleteSelected').addEventListener('click', () => {
                        const selected = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(
                            cb => cb.value);
                        if (selected.length === 0) return alert('Pilih minimal satu customer untuk dihapus.');
                        if (!confirm(`Yakin ingin menghapus ${selected.length} customer terpilih?`)) return;

                        fetch("{{ route('sudirmanpark.bulkDelete') }}", {
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

                    // Fix status badge update
                    document.querySelectorAll('.status-change').forEach(select => {
                        select.addEventListener('change', function() {
                            const id = this.dataset.id;
                            const status = this.value;
                            const row = this.closest('tr');

                            fetch(`/sudirmanpark/${id}/status`, {
                                    method: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        status
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update status badge (td ke-9 sesuai kolom Status)
                                        const badgeCell = row.querySelector('td:nth-child(9) span');
                                        badgeCell.textContent = data.status.charAt(0).toUpperCase() +
                                            data.status.slice(1);
                                        badgeCell.className = 'badge ' + (
                                            status === 'approved' ? 'bg-success' :
                                            status === 'processed' ? 'bg-warning' :
                                            status === 'registration' ? 'bg-info' :
                                            status === 'cancelled' ? 'bg-danger' : ''
                                        );

                                        // Update status change info (td ke-11)
                                        const statusChangeCell = row.querySelector('td:nth-child(11)');
                                        statusChangeCell.textContent = data.status_change;
                                    } else {
                                        alert('Gagal mengubah status.');
                                    }
                                })
                                .catch(() => alert('Terjadi kesalahan koneksi.'));
                        });
                    });
                });
            </script>
        @endpush

        {{-- Edit modal untuk Sudirman Park (AJAX) --}}
        <div class="modal fade" id="sudirmanEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="sudirmanEditForm" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="name" id="edit-name"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" name="phone" id="edit-phone"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" id="edit-email"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tower</label>
                                    <input type="text" name="tower" id="edit-tower"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Paket</label>
                                    <select name="package" id="edit-package" class="form-select form-select-sm"
                                        required>
                                        <option value="">Pilih Paket</option>
                                        <option value="Test Package - Rp 500.000">Test Package - Rp 500.000</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit-status" class="form-select form-select-sm"
                                        required>
                                        <option value="registration">Registration</option>
                                        <option value="processed">Processed</option>
                                        <option value="approved">Approved</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Catatan</label>
                                    <textarea name="note" id="edit-note" class="form-control form-control-sm" rows="2"></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Foto KTP</label>
                                    <div id="current-ktp-area" class="mb-2"></div>
                                    <input type="file" name="ktp" id="edit-ktp"
                                        class="form-control form-control-sm" accept="image/*,.pdf">
                                    <div id="edit-ktp-preview" class="mt-2"></div>
                                </div>
                                @if (\Illuminate\Support\Facades\Schema::hasColumn('sudirman_parks', 'visible'))
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit-visible"
                                                name="visible">
                                            <label class="form-check-label fw-semibold small"
                                                for="edit-visible">Tampilkan
                                                di
                                                daftar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    function init() {
                        // tunggu bootstrap modal tersedia
                        if (typeof bootstrap === 'undefined' || !document.getElementById('sudirmanEditModal')) {
                            return setTimeout(init, 100);
                        }

                        const editModalEl = document.getElementById('sudirmanEditModal');
                        const editModal = new bootstrap.Modal(editModalEl);
                        const form = document.getElementById('sudirmanEditForm');
                        let currentId = null;

                        // buka modal saat tombol edit diklik
                        document.querySelectorAll('.btn-edit-ajax').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const id = this.dataset.id;
                                currentId = id;
                                fetch(`/sudirmanpark/${id}/edit`, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(r => r.json())
                                    .then(data => {
                                        document.getElementById('edit-name').value = data.name || '';
                                        document.getElementById('edit-phone').value = data.phone || '';
                                        document.getElementById('edit-email').value = data.email || '';
                                        document.getElementById('edit-tower').value = data.tower || '';
                                        // set package select if exists
                                        const pkg = document.getElementById('edit-package');
                                        if (pkg) {
                                            pkg.value = data.package || '';
                                        }
                                        const statusEl = document.getElementById('edit-status');
                                        if (statusEl) {
                                            statusEl.value = data.status || 'registration';
                                        }
                                        document.getElementById('edit-note').value = data.note || '';

                                        const preview = document.getElementById('edit-ktp-preview');
                                        preview.innerHTML = '';
                                        const currentArea = document.getElementById('current-ktp-area');
                                        currentArea.innerHTML = '';
                                        if (data.ktp) {
                                            const lower = data.ktp.toLowerCase();
                                            const link = document.createElement('a');
                                            link.href = `/storage/ktp/${data.ktp}`;
                                            link.target = '_blank';
                                            link.textContent = 'Lihat KTP saat ini';
                                            link.className = 'me-2';
                                            currentArea.appendChild(link);

                                            // Hapus KTP button (AJAX)
                                            const delBtn = document.createElement('button');
                                            delBtn.type = 'button';
                                            delBtn.className = 'btn btn-sm btn-outline-danger';
                                            delBtn.textContent = 'Hapus KTP';
                                            delBtn.addEventListener('click', function() {
                                                if (!confirm('Yakin ingin menghapus file KTP?'))
                                                    return;
                                                fetch(`/sudirmanpark/${id}/ktp`, {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': document
                                                                .querySelector(
                                                                    'meta[name="csrf-token"]'
                                                                ).getAttribute(
                                                                    'content'),
                                                            'X-Requested-With': 'XMLHttpRequest'
                                                        }
                                                    })
                                                    .then(r => r.json())
                                                    .then(j => {
                                                        if (j.success) {
                                                            currentArea.innerHTML =
                                                                '<span class="text-muted">No File</span>';
                                                            preview.innerHTML = '';
                                                            Swal.fire('Berhasil',
                                                                'File KTP dihapus',
                                                                'success');
                                                        } else {
                                                            Swal.fire('Gagal',
                                                                'Tidak dapat menghapus file',
                                                                'error');
                                                        }
                                                    })
                                                    .catch(() => Swal.fire('Error',
                                                        'Terjadi kesalahan', 'error'));
                                            });
                                            currentArea.appendChild(delBtn);

                                            if (lower.endsWith('.pdf')) {
                                                const iframe = document.createElement('iframe');
                                                iframe.src = `/sudirmanpark/${id}/ktp/preview`;
                                                iframe.style.width = '100%';
                                                iframe.style.height = '400px';
                                                preview.appendChild(iframe);
                                            } else {
                                                const img = document.createElement('img');
                                                img.src = `/storage/ktp/${data.ktp}`;
                                                img.style.maxWidth = '220px';
                                                img.style.display = 'block';
                                                preview.appendChild(img);
                                            }
                                        } else {
                                            currentArea.innerHTML =
                                                '<span class="text-muted">No File</span>';
                                        }

                                        // visible checkbox
                                        const vis = document.getElementById('edit-visible');
                                        if (vis) {
                                            vis.checked = !!data.visible;
                                        }

                                        editModal.show();
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        Swal.fire('Error', 'Gagal mengambil data. Cek console.',
                                            'error');
                                    });
                            });
                        });

                        // preview saat pilih file baru
                        const ktpInput = document.getElementById('edit-ktp');
                        if (ktpInput) {
                            ktpInput.addEventListener('change', function(e) {
                                const file = e.target.files[0];
                                const preview = document.getElementById('edit-ktp-preview');
                                preview.innerHTML = '';
                                if (!file) return;
                                if (file.type === 'application/pdf') {
                                    const iframe = document.createElement('iframe');
                                    iframe.style.width = '100%';
                                    iframe.style.height = '400px';
                                    const reader = new FileReader();
                                    reader.onload = function(ev) {
                                        iframe.src = ev.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                    preview.appendChild(iframe);
                                } else {
                                    const reader = new FileReader();
                                    reader.onload = function(ev) {
                                        const img = document.createElement('img');
                                        img.src = ev.target.result;
                                        img.style.maxWidth = '220px';
                                        preview.appendChild(img);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        }

                        // submit form via AJAX (FormData)
                        if (form) {
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                if (!currentId) return Swal.fire('Error', 'ID tidak ditemukan', 'error');

                                const fd = new FormData(form);
                                fd.append('_method', 'PUT');

                                fetch(`/sudirmanpark/${currentId}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                                .getAttribute('content')
                                        },
                                        body: fd
                                    })
                                    .then(async r => {
                                        const json = await r.json().catch(() => ({
                                            success: false,
                                            message: 'Invalid JSON'
                                        }));
                                        if (r.ok && json.success) {
                                            editModal.hide();
                                            Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: json.message || 'Perubahan tersimpan'
                                                })
                                                .then(() => window.location.href =
                                                    '{{ route('sudirmanpark.index') }}');
                                        } else {
                                            const msg = json.message || 'Gagal menyimpan. Cek input.';
                                            Swal.fire('Gagal', msg, 'error');
                                        }
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                                    });
                            });
                        }
                    }

                    init();
                });
            </script>
        @endpush
        @push('scripts')
            <script>
                // KTP preview modal logic
                document.addEventListener('DOMContentLoaded', function() {
                    const modalHtml = `
                <div class="modal fade" id="ktpPreviewModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">ID Card Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="height: 80vh; background-color: #f8f9fa;">
                                <div id="ktpSpinner" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <img id="ktpPreviewImage" alt="ID Card Preview" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;" />
                                <iframe id="ktpPreviewFrame" style="width: 100%; height: 100%; border: 0; display: none;" frameborder="0"></iframe>
                                <div id="ktpPreviewError" class="text-center" style="display: none;">
                                    <p class="mb-0">File not found or could not be displayed.</p>
                                    <small>Please check if the file was uploaded correctly.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

                    document.body.insertAdjacentHTML('beforeend', modalHtml);

                    const ktpModalEl = document.getElementById('ktpPreviewModal');
                    const ktpModal = new bootstrap.Modal(ktpModalEl);
                    const spinner = document.getElementById('ktpSpinner');
                    const img = document.getElementById('ktpPreviewImage');
                    const frame = document.getElementById('ktpPreviewFrame');
                    const errorMsg = document.getElementById('ktpPreviewError');

                    document.querySelectorAll('.ktp-preview-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const previewUrl = this.dataset.previewUrl;
                            const filename = this.dataset.ktp.toLowerCase();

                            // Reset state and show spinner
                            img.style.display = 'none';
                            frame.style.display = 'none';
                            errorMsg.style.display = 'none';
                            spinner.style.display = 'block';

                            ktpModal.show();

                            if (filename.endsWith('.pdf')) {
                                frame.src = previewUrl;
                                frame.onload = () => {
                                    spinner.style.display = 'none';
                                    frame.style.display = 'block';
                                };
                                // Note: iframe onerror is not reliable
                            } else {
                                img.src = previewUrl;
                                img.onload = () => {
                                    spinner.style.display = 'none';
                                    img.style.display = 'block';
                                };
                                img.onerror = () => {
                                    spinner.style.display = 'none';
                                    errorMsg.style.display = 'block';
                                };
                            }
                        });
                    });

                    ktpModalEl.addEventListener('hidden.bs.modal', function() {
                        img.src = '';
                        frame.src = 'about:blank';
                        img.style.display = 'none';
                        frame.style.display = 'none';
                        errorMsg.style.display = 'none';
                        spinner.style.display = 'none';
                    });
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Select all
                    document.getElementById('selectAllCustomers').addEventListener('change', function() {
                        document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = this.checked);
                    });

                    // Bulk delete
                    document.getElementById('deleteSelectedCustomers').addEventListener('click', () => {
                        const selected = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(
                            cb => cb.value);
                        if (selected.length === 0) return alert('Pilih minimal satu customer untuk dihapus.');
                        if (!confirm(`Yakin ingin menghapus ${selected.length} customer terpilih?`)) return;

                        fetch("{{ route('sudirmanpark.bulkDelete') }}", {
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
        @endpush
