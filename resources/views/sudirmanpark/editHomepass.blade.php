@extends('layouts.app')

@section('title', 'Edit Homepass - Sudirman Park')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4 sudirmanpark-page">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="modal-title fw-bold fs-5">Edit Homepass</h5>
            <a href="{{ route('sudirmanpark.alamat') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

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

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('sudirmanpark.updateHomepass', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Tower --}}
                        <div class="col-md-4">
                            <label for="tower" class="form-label fw-semibold">Tower <span class="text-danger">*</span></label>
                            <input type="text" name="tower" id="tower" class="form-control"
                                placeholder="Contoh: A, B, C" value="{{ old('tower', $address->tower) }}" required>
                            <small class="text-muted">Contoh: A, B, C</small>
                        </div>

                        {{-- Floor --}}
                        <div class="col-md-4">
                            <label for="floor" class="form-label fw-semibold">Floor <span class="text-danger">*</span></label>
                            <input type="text" name="floor" id="floor" class="form-control"
                                placeholder="Contoh: 01, 02, 07, 15" value="{{ old('floor', $address->floor) }}" required>
                            <small class="text-muted">Contoh: 01, 02, 07, 15</small>
                        </div>

                        {{-- Unit --}}
                        <div class="col-md-4">
                            <label for="unit" class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="unit" class="form-control"
                                placeholder="Contoh: AA, BB, 01, 02" value="{{ old('unit', $address->unit) }}" required>
                            <small class="text-muted">Contoh: AA, BB, 01, 02</small>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="Active" id="status"
                            name="status" {{ old('status', $address->is_active ? 'Active' : 'Inactive') === 'Active' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="status">
                            Status Aktif
                        </label>
                    </div>

                    {{-- Preview --}}
                    <div class="alert alert-info mt-3 mb-0" id="preview-box">
                        <strong>Preview Alamat:</strong>
                        <span id="preview-text">{{ strtoupper($address->tower . '-' . $address->floor . '-' . $address->unit) }}</span>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Update Homepass
                        </button>
                    </div>
                </form>
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
            const towerInput = document.getElementById('tower');
            const floorInput = document.getElementById('floor');
            const unitInput = document.getElementById('unit');
            const previewText = document.getElementById('preview-text');
            const statusCheckbox = document.getElementById('status');

            function updatePreview() {
                const tower = (towerInput.value || '').toUpperCase();
                const floor = (floorInput.value || '').toUpperCase();
                const unit = (unitInput.value || '').toUpperCase();
                previewText.textContent = [tower, floor, unit].filter(Boolean).join('-');
            }

            [towerInput, floorInput, unitInput].forEach(el => {
                el.addEventListener('input', () => {
                    el.value = el.value.toUpperCase();
                    updatePreview();
                });
            });

            // Ensure checkbox sends Active/Inactive in line with controller logic
            const form = statusCheckbox.closest('form');
            form.addEventListener('submit', () => {
                if (statusCheckbox.checked) {
                    statusCheckbox.value = 'Active';
                } else {
                    // add hidden input to send inactive value so validator passes
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'status';
                    hidden.value = 'Inactive';
                    form.appendChild(hidden);
                }
            });

            updatePreview();
        });
    </script>
@endpush
