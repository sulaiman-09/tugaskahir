@extends('layouts.app')

@section('title', 'Edit Customer Sudirman Park')

@section('content')
<div class="page-header mb-4">
<h1 class="page-title">Edit Customer - Sudirman Park</h1>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
<i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Error Messages --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-body bg-light-subtle p-4">
        <form action="{{ route('sudirmanpark.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama Customer --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Nama Customer</h6>
                <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('name', $customer->name) }}" required>
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Nomor Telepon</h6>
                <input type="text" name="phone" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('phone', $customer->phone) }}" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Email</h6>
                <input type="email" name="email" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('email', $customer->email) }}">
            </div>

            {{-- Alamat Tower --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Alamat Tower</h6>
                <input type="text" name="tower" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('tower', $customer->tower) }}" required>
            </div>

            {{-- Paket --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Paket</h6>
                <select name="package" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                    <option value="">Pilih Paket</option>
                    <option value="Test Package - Rp 500.000"
                        {{ old('package', $customer->package) == 'Test Package - Rp 500.000' ? 'selected' : '' }}>
                        Test Package - Rp 500.000
                    </option>
                </select>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Status</h6>
                <select name="status" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                    <option value="registration" {{ old('status', $customer->status) == 'registration' ? 'selected' : '' }}>
                        Registration</option>
                    <option value="processed" {{ old('status', $customer->status) == 'processed' ? 'selected' : '' }}>
                        Processed</option>
                    <option value="approved" {{ old('status', $customer->status) == 'approved' ? 'selected' : '' }}>Approved
                    </option>
                    <option value="cancelled" {{ old('status', $customer->status) == 'cancelled' ? 'selected' : '' }}>
                        Cancelled</option>
                </select>
            </div>

            {{-- Foto KTP --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Foto KTP</h6>
                @if ($customer->ktp)
                    <div class="mb-2">
                        <a href="{{ asset('storage/ktp/' . $customer->ktp) }}" target="_blank" class="text-primary">
                            Lihat KTP saat ini
                        </a>
                        <form action="{{ route('sudirmanpark.removeKtp', $customer->id) }}" method="POST" class="d-inline ms-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus KTP</button>
                        </form>
                    </div>
                @endif
                <input type="file" name="ktp" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    accept="image/*,.pdf">
            </div>

            {{-- Tampilkan di daftar --}}
            @if (Schema::hasColumn('sudirman_parks', 'visible'))
                <div class="mb-3 form-check">
                    <input type="checkbox" name="visible" class="form-check-input" id="visibleCheck"
                        {{ old('visible', $customer->visible) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="visibleCheck">Tampilkan di daftar</label>
                </div>
            @endif

            {{-- Catatan --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Catatan</h6>
                <textarea name="note" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="3">{{ old('note', $customer->note) }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end">
                <a href="{{ route('sudirmanpark.index') }}"
                    class="btn btn-outline-secondary px-4 rounded-3 fw-semibold me-2">Batal</a>
                <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">Perbarui</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfirmasi hapus KTP
            const removeKtpForm = document.querySelector('form[action*="/remove-ktp"]');
            if (removeKtpForm) {
                removeKtpForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Yakin ingin menghapus file KTP? Aksi ini tidak dapat dibatalkan.')) {
                        removeKtpForm.submit();
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
