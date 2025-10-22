@extends('layouts.app')

@section('title', 'Sudirman Park - Alamat Homepass')

@section('content')
    <div class="container py-4">

        {{-- Judul --}}
        <h3 class="fw-bold mb-4 text-dark">Kelola Alamat Homepass - Sudirman Park</h3>

        {{-- Tombol Aksi --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body py-3 d-flex flex-wrap gap-2 align-items-center">

                {{-- Export --}}
                <a href="{{ route('sudirmanpark.exportHomepass', request()->query()) }}"
                    class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                    <i class="fa fa-print me-2"></i> Export CSV
                </a>
                
                {{-- Tambah Alamat --}}
                <a href="{{ route('sudirmanpark.createHomepass') }}"
                    class="btn btn-sm {{ request()->routeIs('sudirmanpark.createHomepass') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                    + Tambah Homepass
                </a>

                {{-- Search --}}
                <form action="{{ route('sudirmanpark.alamat') }}" method="GET" class="d-flex align-items-center ms-auto"
                    style="max-width: 420px; width:100%;">
                    <input type="text" name="q" class="form-control form-control-sm"
                        placeholder="Search tower, floor, or unit" value="{{ $q ?? request('q') }}">
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
                                <th>Tower</th>
                                <th>Floor</th>
                                <th>Unit</th>
                                <th>Alamat Lengkap</th>
                                <th>Jumlah Customer</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addresses as $index => $address)
                                <tr>
                                    <td>{{ $addresses->firstItem() + $index }}</td>
                                    <td>{{ $address->tower }}</td>
                                    <td>{{ $address->floor }}</td>
                                    <td>{{ $address->unit }}</td>
                                    <td class="text-start ps-3">{{ $address->alamat_lengkap }}</td>
                                    <td>{{ $address->jumlah_customer ?? 0 }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $address->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($address->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $address->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('sudirmanpark.editHomepass', $address->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('sudirmanpark.destroyHomepass', $address->id) }}"
                                                method="POST" class="delete-form"
                                                data-name="{{ $address->tower }} - {{ $address->unit }}">
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
                                    <td colspan="9" class="text-muted text-center py-4">Belum ada data alamat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $addresses->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .btn-outline-primary {
            border: 1.5px solid #007bff;
            color: #007bff;
            background: #fff;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
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
        });
    </script>
@endpush
