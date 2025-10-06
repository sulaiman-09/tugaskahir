@extends('layouts.app')

@section('title', 'Create Division')

@section('content')
<div class="container">
    <h2>Create Division</h2>

    <form action="{{ route('division.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name') }}" 
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea 
                name="description" 
                id="description" 
                class="form-control @error('description') is-invalid @enderror" 
                rows="3"
            >{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input 
                type="checkbox" 
                name="status" 
                id="status" 
                value="1" 
                class="form-check-input" 
                {{ old('status', true) ? 'checked' : '' }}
            >
            <label for="status" class="form-check-label">Active</label>
        </div>

        <div class="d-flex">
            <a href="{{ route('division.index') }}" class="btn btn-secondary me-2">Back</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection