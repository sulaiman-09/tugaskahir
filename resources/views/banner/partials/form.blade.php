@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Error box untuk validasi AJAX --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data"
    class="banner-edit-form">
    @csrf
    @method('PUT')

    <div class="modal-body py-3">

        {{-- Banner Basic Information --}}
        <div class="mb-4">
            <h6 class="fw-bold fs-6 mb-3">Banner Information</h6>
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label small">Banner Name</label>
                    <input type="text" name="name" class="form-control rounded-3 shadow-sm border-0 bg-white"
                        placeholder="Enter banner name" value="{{ old('name', $banner->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label small">Status</label>
                    <select name="is_active" class="form-select rounded-3 shadow-sm border-0 bg-white" required>
                        <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Banner Images --}}
        <div class="mb-4">
            <h6 class="fw-bold fs-6 mb-3">Banner Images</h6>
            <div class="row g-4">

                {{-- Web Image --}}
                <div class="col-md-6">
                    <label class="form-label small">Web Image</label>
                    <div class="mb-2">
                        @if ($banner->path)
                            <img src="{{ asset('storage/' . $banner->path) }}" class="rounded-3 shadow-sm d-block"
                                style="max-height:150px; object-fit:cover;">
                        @else
                            <p class="text-muted small m-0">No image uploaded.</p>
                        @endif
                    </div>
                    <input type="file" name="web_image" class="form-control rounded-3 shadow-sm border-0 bg-white">
                </div>

                {{-- Mobile Image --}}
                <div class="col-md-6">
                    <label class="form-label small">Mobile Image</label>
                    <div class="mb-2">
                        @if ($banner->path_apps)
                            <img src="{{ asset('storage/' . $banner->path_apps) }}" class="rounded-3 shadow-sm d-block"
                                style="max-height:150px; object-fit:cover;">
                        @else
                            <p class="text-muted small m-0">No image uploaded.</p>
                        @endif
                    </div>
                    <input type="file" name="mobile_image"
                        class="form-control rounded-3 shadow-sm border-0 bg-white">
                </div>

            </div>
        </div>

    </div>

    {{-- Buttons --}}
    <div class="modal-footer sticky-bottom bg-white"
        style="z-index: 2; border-top: 1px solid #f1f5f9;">
        <button type="button" class="btn btn-secondary btn-sm"
            data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
    </div>

</form>
