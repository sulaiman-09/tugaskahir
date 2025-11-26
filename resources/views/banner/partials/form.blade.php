@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Error box untuk validasi AJAX --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="banner-edit-form">
    @csrf
    @method('PUT')

    {{-- Data Utama --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Banner Information</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Banner Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    placeholder="Enter banner name" value="{{ old('name', $banner->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                    <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Upload Gambar --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Banner Images</h6>
        <div class="row g-3">
            {{-- Web Image --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Web Image</label><br>
                @if ($banner->path)
                    <img src="{{ asset('storage/' . $banner->path) }}" alt="Web Image"
                        class="rounded shadow-sm mb-2 d-block" width="200">
                @else
                    <p class="text-muted">No image uploaded.</p>
                @endif
                <input type="file" name="web_image" class="form-control rounded-3 shadow-sm border-0 bg-white">
            </div>

            {{-- Mobile Image --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Mobile Image</label><br>
                @if ($banner->path_apps)
                    <img src="{{ asset('storage/' . $banner->path_apps) }}" alt="Mobile Image"
                        class="rounded shadow-sm mb-2 d-block" width="200">
                @else
                    <p class="text-muted">No image uploaded.</p>
                @endif
                <input type="file" name="mobile_image" class="form-control rounded-3 shadow-sm border-0 bg-white">
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
        @unless($hideCancel)
            <a href="{{ route('banner.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                Cancel
            </a>
        @endunless
        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
            Update Banner
        </button>
    </div>
</form>
