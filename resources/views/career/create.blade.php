@extends('layouts.app')

@section('title', 'Create Career')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3 text-dark">Create Career</h4>

        <form action="{{ route('career.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Job Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter job title" value="{{ old('title') }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Employment Type --}}
            <div class="mb-3">
                <label for="type" class="form-label">Employment Type</label>
                <select class="form-select" id="type" name="type">
                    <option value="Full Time">Full Time</option>
                    <option value="Contract">Contract</option>
                    <option value="Internship">Internship</option>
                </select>
            </div>

            {{-- Education Level --}}
            <div class="mb-3">
                <label for="education_level" class="form-label">Education Level</label>
                <select class="form-select" id="education_level" name="education_level">
                    <option value="SMA/SMK">SMA/SMK</option>
                    <option value="Diploma">Diploma</option>
                    <option value="S1" selected>S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>

            {{-- Location --}}
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Enter location">
            </div>

            {{-- Job Overview --}}
            <div class="mb-3">
                <label for="overview" class="form-label">Job Overview</label>
                <textarea class="form-control" id="overview" name="overview" rows="3" placeholder="Enter job overview"></textarea>
            </div>

            {{-- Cover Image --}}
            <div class="mb-3">
                <label for="image" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            {{-- Job Requirements --}}
            <div class="mb-3">
                <label class="form-label">Job Requirements</label>
                <div id="requirements-wrapper" class="d-flex flex-column gap-2">
                    <div class="d-flex gap-2 requirement-item">
                        <input type="text" name="job_requirements[]" class="form-control" placeholder="Add a requirement">
                        <button type="button" class="btn btn-danger btn-sm remove-requirement">Delete</button>
                    </div>
                </div>
                <button type="button" id="add-requirement" class="btn btn-sm btn-primary mt-2">+ Add Requirement</button>
            </div>

            {{-- Job Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Add job description"></textarea>
            </div>

            {{-- Active Checkbox --}}
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                <label for="is_active" class="form-check-label">Active</label>
            </div>

            {{-- Buttons --}}
            <a href="{{ route('career.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Career</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('requirements-wrapper');
        const addBtn = document.getElementById('add-requirement');

        addBtn.addEventListener('click', function () {
            const div = document.createElement('div');
            div.classList.add('d-flex', 'gap-2', 'requirement-item');
            div.innerHTML = `
                <input type="text" name="job_requirements[]" class="form-control" placeholder="Add a requirement">
                <button type="button" class="btn btn-danger btn-sm remove-requirement">Delete</button>
            `;
            wrapper.appendChild(div);
        });

        wrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-requirement')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>
@endpush
