@extends('layouts.app')

@section('title', 'Create Career')

@section('content')
 <div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold text-black">Create Career</h5>
        </div>

        {{-- Body --}}
        <div class="card-body bg-light-subtle p-4">
            <form action="{{ route('career.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Error Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Job Information --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-black border-start border-3 ps-2 mb-3">Job Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                   class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Enter job title"
                                   value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Employment Type</label>
                            <select name="type" class="form-select rounded-3 shadow-sm border-0 bg-white">
                                @foreach (['Fulltime', 'Contract', 'Internship'] as $type)
                                    <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Education Level</label>
                            <select name="education_level" class="form-select rounded-3 shadow-sm border-0 bg-white">
                                @foreach (['SMA/SMK', 'Diploma', 'S1', 'S2', 'S3'] as $edu)
                                    <option value="{{ $edu }}" {{ old('education_level') === $edu ? 'selected' : '' }}>
                                        {{ $edu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Location & Description --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-black border-start border-3 ps-2 mb-3">Location & Description</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Location</label>
                            <input type="text" name="location"
                                   class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Enter location"
                                   value="{{ old('location') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Short Description</label>
                            <textarea name="description" rows="2"
                                      class="form-control rounded-3 shadow-sm border-0 bg-white"
                                      placeholder="Enter short job description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Job Description --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-black border-start border-3 ps-2 mb-3">Job Description</h6>
                    <textarea name="job_description" rows="4"
                              class="form-control rounded-3 shadow-sm border-0 bg-white"
                              placeholder="Enter detailed job description">{{ old('job_description') }}</textarea>
                </div>

                {{-- Job Requirements --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-black border-start border-3 ps-2 mb-3">Job Requirements</h6>
                    <div id="requirements-wrapper" class="d-flex flex-column gap-2">
                        <div class="d-flex gap-2 requirement-item">
                            <input type="text" name="job_requirements[]"
                                   class="form-control rounded-3 shadow-sm border-0 bg-white"
                                   placeholder="Add a requirement">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">Delete</button>
                        </div>
                    </div>
                    <button type="button" id="add-requirement" class="btn btn-outline-primary btn-sm mt-2">
                        + Add Requirement
                    </button>
                </div>

                {{-- Image & Status --}}
                <div class="mb-4">
                    <h6 class="fw-semibold text-black border-start border-3 ps-2 mb-3">Cover & Status</h6>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Cover Image</label>
                            <input type="file" name="image_path"
                                   class="form-control rounded-3 shadow-sm border-0 bg-white">
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" name="is_active" checked>
                            <label class="form-check-label small">Active</label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <a href="{{ route('career.index') }}"
                       class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Create Career
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.getElementById('requirements-wrapper');
                const addBtn = document.getElementById('add-requirement');
                addBtn.addEventListener('click', function() {
                    const div = document.createElement('div');
                    div.classList.add('d-flex', 'gap-2', 'requirement-item');
                    div.innerHTML = `
                <input type="text" name="job_requirements[]" class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Add a requirement">
                <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">Delete</button>
            `;
                    wrapper.appendChild(div);
                });
                wrapper.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-requirement')) e.target.parentElement.remove();
                });
            });
        </script>
    @endpush
@endsection
