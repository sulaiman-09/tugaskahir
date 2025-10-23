@extends('layouts.app')

@section('title', 'Sudirman Park - Customer Management')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4 text-dark">Sudirman Park - Customer Management</h3>

        {{-- Tombol Aksi --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body py-3 d-flex flex-wrap gap-2 align-items-center">

                {{-- Export CSV --}}
                <a href="{{ route('sudirmanpark.export', request()->query()) }}"
                    class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                    <i class="fa fa-print me-2"></i> Export CSV
                </a>

                <a href="{{ route('sudirmanpark.create') }}"
                    class="btn btn-sm {{ request()->routeIs('sudirmanpark.create') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    + Tambah Customer Baru
                </a>

                <a href="{{ route('sudirmanpark.alamat') }}"
                    class="btn btn-sm {{ request()->routeIs('sudirmanpark.alamat') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    Kelola Alamat Tower
                </a>

                <a href="{{ route('product.index') }}"
                    class="btn btn-sm {{ request()->routeIs('product.*') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    Kelola Produk
                </a>

                {{-- Search --}}
                <form action="{{ route('sudirmanpark.index') }}" method="GET" class="d-flex align-items-center ms-auto"
                    style="max-width: 420px; width:100%;">
                    <input type="text" name="q" class="form-control form-control-sm"
                        placeholder="Search name, phone or email" value="{{ $q ?? request('q') }}">
                    <input type="hidden" name="show_all" value="{{ $showAll ? '1' : '0' }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center table-striped table-borderless">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr class="fw-semibold text-dark">
                                <th style="width: 40px;">No</th>
                                <th>Nama Customer</th>
                                <th>No. Telepon</th>
                                <th>Email</th>
                                <th>Alamat Tower</th>
                                <th>Paket</th>
                                <th>ID Card</th>
                                <th>Status</th>
                                <th>Change Status</th>
                                <th>Status Update Info</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $customer)
                                <tr>
                                    <td>{{ $customers->firstItem() + $index }}</td>
                                    <td class="text-start ps-3">{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->tower }}</td>
                                    <td>{{ $customer->package }}</td>
                                    <td>
                                        @if ($customer->ktp)
                                            <a href="{{ route('sudirmanpark.downloadKtp', $customer->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-secondary">View</a>
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge 
                                        @if ($customer->status == 'approved') bg-success
                                        @elseif($customer->status == 'processed') bg-warning
                                        @elseif($customer->status == 'registration') bg-info
                                        @elseif($customer->status == 'cancelled') bg-danger @endif">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status-change"
                                            data-id="{{ $customer->id }}">
                                            <option value="registration"
                                                {{ $customer->status == 'registration' ? 'selected' : '' }}>Registration
                                            </option>
                                            <option value="processed"
                                                {{ $customer->status == 'processed' ? 'selected' : '' }}>
                                                Processed</option>
                                            <option value="approved"
                                                {{ $customer->status == 'approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="cancelled"
                                                {{ $customer->status == 'cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                    </td>
                                    <td>{{ $customer->status_change ?? '-' }}</td>
                                    <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('sudirmanpark.edit', $customer->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
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
                                    <td colspan="12" class="text-muted text-center py-4">Belum ada data customer</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $customers->links() }}
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

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
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

            // Change Status (AJAX)
            document.querySelectorAll('.status-change').forEach(select => {
                select.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const status = this.value;
                    const row = this.closest('tr');
                    const badgeCell = row.querySelector('td:nth-child(8) span');

                    fetch(`/sudirmanpark/${id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                status
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                badgeCell.textContent = data.status;
                                badgeCell.className = 'badge ' + (
                                    status === 'approved' ? 'bg-success' :
                                    status === 'processed' ? 'bg-warning' :
                                    status === 'registration' ? 'bg-info' :
                                    status === 'cancelled' ? 'bg-danger' : ''
                                );
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
