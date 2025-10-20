@extends('layouts.app')

@section('title', 'Sudirman Park - Customer Management')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm p-4">

            {{-- Judul --}}
            <h4 class="fw-bold mb-3 text-dark">Sudirman Park - Customer Management</h4>

            {{-- Tombol Aksi --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('sudirmanpark.create') }}"
                    class="btn {{ request()->routeIs('sudirmanpark.create') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    + Tambah Customer Baru
                </a>

                <a href="{{ route('sudirmanpark.alamat') }}"
                    class="btn {{ request()->routeIs('sudirmanpark.alamat') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    Kelola Alamat Tower
                </a>

                <a href="{{ route('product.index') }}"
                    class="btn {{ request()->routeIs('product.*') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    Kelola Produk
                </a>
            </div>

            {{-- Tombol Export CSV --}}
            <div class="d-flex gap-2 mb-2">
                {{-- Export CSV Dropdown (ganti dari Print) --}}
                <div class="dropdown position-relative" style="z-index: 1055;">
                    <a href="{{ route('sudirmanpark.export', request()->query()) }}"
                        class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center"
                        title="Export Data CSV">
                        <i class="fa fa-file-csv me-1"></i> Export CSV
                    </a>
                </div>

                {{-- Search (di kanan) --}}
                <form action="{{ route('sudirmanpark.index') }}" method="GET" class="d-flex align-items-center ms-auto"
                    style="max-width: 420px; width:100%;">
                    <input type="text" name="q" class="form-control form-control-sm"
                        placeholder="Search name, phone or email" value="{{ $q ?? request('q') }}">
                    <input type="hidden" name="show_all" value="{{ $showAll ? '1' : '0' }}">
                    <button type="submit" class="btn btn-primary btn-sm ms-2" style="border-radius:8px;">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            @php
                                $columns = [
                                    'No',
                                    'Nama Customer',
                                    'No. Telepon',
                                    'Email',
                                    'Alamat Tower',
                                    'Paket',
                                    'ID Card',
                                    'Status',
                                    'Change Status',
                                    'Status Update Info',
                                    'Tanggal Dibuat',
                                    'Aksi',
                                ];
                            @endphp
                            @foreach ($columns as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                            <tr>
                                <td>{{ $customers->firstItem() + $index }}</td>
                                <td class="text-start">{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->tower }}</td>
                                <td>{{ $customer->package }}</td>
                                <td>
                                    @if ($customer->ktp)
                                        <a href="{{ asset('storage/ktp/' . $customer->ktp) }}" target="_blank"
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
                                    <select class="form-select form-select-sm status-change" data-id="{{ $customer->id }}">
                                        <option value="registration"
                                            {{ $customer->status == 'registration' ? 'selected' : '' }}>Registration
                                        </option>
                                        <option value="processed" {{ $customer->status == 'processed' ? 'selected' : '' }}>
                                            Processed</option>
                                        <option value="approved" {{ $customer->status == 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="cancelled" {{ $customer->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </td>
                                <td>{{ $customer->status_change ?? '-' }}</td>
                                <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('sudirmanpark.edit', $customer->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit"><i
                                            class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('sudirmanpark.destroy', $customer->id) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">Belum ada data customer</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // === Change Status (AJAX) ===
        document.querySelectorAll('.status-change').forEach(select => {
            select.addEventListener('change', function() {
                const id = this.dataset.id;
                const status = this.value;
                const row = this.closest('tr');
                const badgeCell = row.querySelector('td:nth-child(8) span'); // kolom status

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
                            // Ubah tampilan kolom status di tabel
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
