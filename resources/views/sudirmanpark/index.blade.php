@extends('layouts.app')

@section('title', 'Sudirman Park - Customer Management')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Sudirman Park - Customer Management</h1>
    <a href="{{ route('sudirmanpark.create') }}" class="btn btn-primary mt-2">+ Tambah Customer Baru</a>
</div>

<div class="card shadow-sm p-4">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Alamat Tower</th>
                <th>Paket</th>
                <th>ID Card</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->tower }}</td>
                    <td>{{ $customer->package }}</td>
                    <td>
                        @if($customer->ktp)
                            <a href="{{ asset('uploads/ktp/'.$customer->ktp) }}" target="_blank" class="btn btn-sm btn-secondary">View</a>
                        @else
                            <span class="text-muted">No file</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge 
                            @if($customer->status == 'approved') bg-success 
                            @elseif($customer->status == 'processed') bg-warning 
                            @elseif($customer->status == 'registration') bg-info 
                            @else bg-danger @endif">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td>{{ $customer->note ?? '-' }}</td>
                    <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="#" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus customer ini?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">Belum ada data customer.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
