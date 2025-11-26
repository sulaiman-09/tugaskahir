@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Error box untuk validasi AJAX --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('career.update', $career->id) }}" method="POST" enctype="multipart/form-data" class="career-edit-form">
    @csrf
    @method('PUT')

    {{-- Job Information --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Job Information</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Job Title</label>
                <input type="text" name="title" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('title', $career->title) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small">Employment Type</label>
                <select name="type" class="form-select rounded-3 shadow-sm border-0 bg-white">
                    @foreach (['Fulltime', 'Contract', 'Internship'] as $type)
                        <option value="{{ $type }}" {{ old('type', $career->type) === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small">Education Level</label>
                <select name="education_level" class="form-select rounded-3 shadow-sm border-0 bg-white">
                    @foreach (['SMA/SMK', 'Diploma', 'S1', 'S2', 'S3'] as $edu)
                        <option value="{{ $edu }}" {{ old('education_level', $career->education_level) === $edu ? 'selected' : '' }}>
                            {{ $edu }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Location & Description --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Location & Description</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Location</label>
                <input type="text" name="location" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    value="{{ old('location', $career->location) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Short Description</label>
                <textarea name="description" rows="2" class="form-control rounded-3 shadow-sm border-0 bg-white">{{ old('description', $career->description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Job Description --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Job Description</h6>
        <textarea name="job_description" rows="4" class="form-control rounded-3 shadow-sm border-0 bg-white">{{ old('job_description', $career->job_description) }}</textarea>
    </div>

    {{-- Job Requirements --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Job Requirements</h6>
        <div id="requirements-wrapper" class="d-flex flex-column gap-2">
            @php
                $requirements = is_array($career->job_requirements) ? $career->job_requirements : json_decode($career->job_requirements, true);
            @endphp

            @forelse ($requirements as $req)
                <div class="d-flex gap-2 requirement-item">
                    <input type="text" name="job_requirements[]" class="form-control rounded-3 shadow-sm border-0 bg-white" value="{{ $req }}">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">Delete</button>
                </div>
            @empty
                <div class="d-flex gap-2 requirement-item">
                    <input type="text" name="job_requirements[]" class="form-control rounded-3 shadow-sm border-0 bg-white">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">Delete</button>
                </div>
            @endforelse
        </div>
        <button type="button" id="add-requirement" class="btn btn-outline-primary btn-sm mt-2">+ Add Requirement</button>
    </div>

    {{-- Image & Status --}}
    <div class="mb-4">
        <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-3">Cover & Status</h6>
        <div class="row g-3 align-items-center">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Cover Image</label>
                <input type="file" name="image_path" class="form-control rounded-3 shadow-sm border-0 bg-white">
                @if ($career->image_path)
                    <small class="text-muted d-block mt-1">Current image:</small>
                    <img src="{{ asset($career->image_path) }}" class="img-thumbnail mt-2 rounded-3" width="150">
                @endif
            </div>
            <div class="col-md-4 d-flex align-items-center gap-2">
                <input type="checkbox" class="form-check-input" name="is_active" {{ $career->is_active ? 'checked' : '' }}>
                <label class="form-label fw-semibold small mb-0">Active</label>
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
        @unless($hideCancel)
            <a href="{{ route('career.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        @endunless
        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
            Update Career
        </button>
    </div>
</form>

<script>
    // dynamic requirement add/remove
    (function() {
        const wrapper = document.getElementById('requirements-wrapper');
        if (!wrapper) return;

        function bindRemoveButtons() {
            wrapper.querySelectorAll('.remove-requirement').forEach(btn => {
                btn.onclick = () => {
                    const item = btn.closest('.requirement-item');
                    if (wrapper.children.length > 1) item.remove();
                    else item.querySelector('input').value = '';
                };
            });
        }

        document.getElementById('add-requirement')?.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'd-flex gap-2 requirement-item';
            div.innerHTML = `
                <input type="text" name="job_requirements[]" class="form-control rounded-3 shadow-sm border-0 bg-white">
                <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">Delete</button>
            `;
            wrapper.appendChild(div);
            bindRemoveButtons();
        });

        bindRemoveButtons();
    })();
</script>
