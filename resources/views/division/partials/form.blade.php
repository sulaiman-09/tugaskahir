@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Error box untuk validasi AJAX --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('division.update', $division->id) }}" method="POST" class="division-edit-form">
    @csrf
    @method('PUT')

    {{-- Data Utama --}}
    <div class="mb-4">
        <h6 class="fw-bold text-dark border-start border-3 ps-2 mb-3">Division Information</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold small">Division Name <span
                        class="text-danger">*</span></label>
                <input type="text" name="name" id="name"
                    class="form-control rounded-3 shadow-sm border-0 bg-white @error('name') is-invalid @enderror"
                    placeholder="Enter division name" value="{{ old('name', $division->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label fw-semibold small">Active Status</label>
                <div class="form-check form-switch ms-2 mt-2">
                    <input type="checkbox" name="status" id="status" class="form-check-input shadow-sm"
                        value="1" {{ old('status', $division->status) ? 'checked' : '' }}>
                    <label for="status" class="form-check-label fw-semibold small">Active</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Deskripsi --}}
    <div class="mb-4">
        <h6 class="fw-bold text-dark border-start border-3 ps-2 mb-3">Description</h6>
        <textarea name="description" id="description"
            class="form-control rounded-3 shadow-sm border-0 bg-white @error('description') is-invalid @enderror"
            placeholder="Enter short description..." rows="3">{{ old('description', $division->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tombol Aksi --}}
    <div class="modal-footer sticky-bottom bg-white"
        style="z-index: 2; border-top: 1px solid #f1f5f9;">
        <button type="button" class="btn btn-secondary btn-sm"
            data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
    </div>
</form>
