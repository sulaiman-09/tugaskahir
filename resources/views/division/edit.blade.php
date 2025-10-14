@extends('layouts.app')

@section('title', 'Edit Division')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">Edit Division</h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('division.update', $division->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Division --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Division Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Enter division name"
                                value="{{ old('name', $division->name) }}" 
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea 
                                name="description" 
                                id="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                placeholder="Enter short description..."
                                rows="3"
                            >{{ old('description', $division->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="form-check form-switch mb-4">
                            <input 
                                type="checkbox" 
                                name="status" 
                                id="status" 
                                class="form-check-input" 
                                value="1" 
                                {{ old('status', $division->status) ? 'checked' : '' }}
                            >
                            <label for="status" class="form-check-label fw-semibold">
                                Active Status
                            </label>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('division.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-1"></i> Update Division
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
