@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Error box untuk validasi AJAX --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('settings-content.update', $content->id) }}" method="POST" enctype="multipart/form-data"
    class="settings-content-form">
    @csrf
    @method('PUT')

    {{-- Data Utama --}}
    <div class="mb-4">
        <h6 class="fw-bold ps-2 mb-3" style="color:black; border-left: 3px solid #000;">Main Data</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Content Type <span class="text-danger">*</span></label>
                <input type="number" name="content_type_id" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('content_type_id', $content->content_type_id) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('title', $content->title) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('name', $content->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Order</label>
                <input type="number" name="order" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('order', $content->order) }}">
            </div>
        </div>
    </div>

    {{-- Deskripsi --}}
    <div class="mb-4">
        <h6 class="fw-bold ps-2 mb-3" style="color:black; border-left: 3px solid #000;">Description</h6>
        <textarea name="description" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="4"
            placeholder="Enter description...">{{ old('description', $content->description) }}</textarea>
    </div>

    {{-- Media --}}
    <div class="mb-4">
        <h6 class="fw-bold ps-2 mb-3" style="color:black; border-left: 3px solid #000;">Media</h6>
        <div class="row g-3">
            {{-- Image --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Image</label>
                <input type="file" name="image" class="form-control rounded-3 shadow-sm border-0 bg-white">
                @if ($content->image_path)
                    <div class="mt-2">
                        <p class="mb-1 small text-muted">Current Image:</p>
                        <img src="{{ Storage::url($content->image_path) }}" alt="Current Image" class="img-thumbnail"
                            style="max-height: 150px;">
                    </div>
                @endif
            </div>

            {{-- Icon --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Icon</label>
                <input type="file" name="icon" class="form-control rounded-3 shadow-sm border-0 bg-white">
                @if ($content->icon_path)
                    <div class="mt-2">
                        <p class="mb-1 small text-muted">Current Icon:</p>
                        <img src="{{ Storage::url($content->icon_path) }}" alt="Current Icon" class="img-thumbnail"
                            style="max-height: 100px;">
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="mb-4">
        <h6 class="fw-bold ps-2 mb-3" style="color:black; border-left: 3px solid #000;">Status</h6>
        <div class="form-check form-switch ms-3">
            <input type="checkbox" name="is_active" class="form-check-input"
                {{ old('is_active', $content->is_active) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold small ms-2">Active</label>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="modal-footer sticky-bottom bg-white" style="z-index: 2; border-top: 1px solid #f1f5f9;">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
    </div>
</form>
