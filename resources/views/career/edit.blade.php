@extends('layouts.app')

@section('title', 'Edit Career')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm p-4">

            <h4 class="fw-bold mb-3 text-dark">Edit Career</h4>

            <form action="{{ route('career.update', $career->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Job Title --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Job Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ old('title', $career->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Employment Type --}}
                <div class="mb-3">
                    <label for="type" class="form-label">Employment Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                        @php
                            $types = ['Full Time', 'Contract', 'Internship'];
                        @endphp
                        @foreach ($types as $type)
                            <option value="{{ $type }}"
                                {{ old('type', $career->type) === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Education Level --}}
                <div class="mb-3">
                    <label for="education" class="form-label">Education Level</label>
                    <select class="form-select @error('education_level') is-invalid @enderror" id="education_level"
                        name="education_level">
                        @php
                            $levels = ['SMA/SMK', 'Diploma', 'S1', 'S2', 'S3'];
                        @endphp
                        @foreach ($levels as $level)
                            <option value="{{ $level }}"
                                {{ old('education_level', $career->education_level) == $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                    @error('education')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Location --}}
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                        name="location" value="{{ old('location', $career->location) }}">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Job Overview --}}
                <div class="mb-3">
                    <label for="overview" class="form-label">Job Overview</label>
                    <textarea class="form-control @error('overview') is-invalid @enderror" id="overview" name="overview" rows="3">{{ old('overview', $career->overview) }}</textarea>
                    @error('overview')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Job Requirements --}}
                <div class="mb-3">
                    <label class="form-label">Job Requirements</label>

                    <div id="requirements-wrapper" class="d-flex flex-column gap-2">
                        @php
                            $requirements = $career->job_requirements ? explode("\n", $career->job_requirements) : [];
                        @endphp

                        @forelse($requirements as $req)
                            <div class="d-flex gap-2 requirement-item">
                                <input type="text" name="job_requirements[]" class="form-control"
                                    value="{{ $req }}" placeholder="Enter requirement">
                                <button type="button" class="btn btn-danger btn-sm remove-requirement">Delete</button>
                            </div>
                        @empty
                            <div class="d-flex gap-2 requirement-item">
                                <input type="text" name="job_requirements[]" class="form-control"
                                    placeholder="Enter requirement">
                                <button type="button" class="btn btn-danger btn-sm remove-requirement">Delete</button>
                            </div>
                        @endforelse
                    </div>

                    <button type="button" id="add-requirement" class="btn btn-sm btn-primary mt-2">+ Add
                        Requirement</button>
                    @error('job_requirements')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Job Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Job Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="5">{{ old('description', $career->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Cover Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Cover Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                        name="image">
                    @if ($career->image)
                        <img src="{{ asset($career->image) }}" alt="Current Image" class="img-thumbnail mt-2"
                            width="150">
                    @endif
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                        required>
                        <option value="Active" {{ old('status', $career->status) === 'Active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="Inactive" {{ old('status', $career->status) === 'Inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Update Career</button>
                <a href="{{ route('career.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

            {{-- Current Career Details --}}
            <div class="mt-4">
                <h5 class="mb-3">Current Career Details</h5>

                <div class="card p-3 shadow-sm">
                    <dl class="row mb-0">
                        <dt class="col-sm-3 fw-semibold">Employment Type</dt>
                        <dd class="col-sm-9">{{ $career->type }}</dd>

                        <dt class="col-sm-3 fw-semibold">Education Level</dt>
                        <dd class="col-sm-9">
                            <input type="text"
                                class="form-control form-control-sm @error('education_level') is-invalid @enderror"
                                id="education_level" name="education_level"
                                value="{{ old('education_level', $career->education_level) }}">
                            @error('education_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </dd>

                        <dt class="col-sm-3 fw-semibold">Location</dt>
                        <dd class="col-sm-9">{{ $career->location }}</dd>

                        <dt class="col-sm-3 fw-semibold">Created</dt>
                        <dd class="col-sm-9">{{ \Carbon\Carbon::parse($career->created_at)->format('d M Y H:i') }}</dd>

                        <dt class="col-sm-3 fw-semibold">Last Updated</dt>
                        <dd class="col-sm-9">{{ \Carbon\Carbon::parse($career->updated_at)->format('d M Y H:i') }}</dd>

                        <dt class="col-sm-3 fw-semibold">Status</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $career->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $career->status }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('requirements-wrapper');
            const addBtn = document.getElementById('add-requirement');

            addBtn.addEventListener('click', function() {
                const div = document.createElement('div');
                div.classList.add('d-flex', 'gap-2', 'requirement-item');
                div.innerHTML = `
            <input type="text" name="job_requirements[]" class="form-control" placeholder="Enter requirement">
            <button type="button" class="btn btn-danger btn-sm remove-requirement">Delete</button>
        `;
                wrapper.appendChild(div);
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-requirement')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endpush
