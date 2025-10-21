@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bold text-dark">Edit Banner</h3>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Banner Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $banner->name }}" required>
            </div>

            <div class="mb-3">
                <label for="web_image" class="form-label">Web Image</label><br>
                @if ($banner->web_image)
                    <img src="{{ asset('storage/'.$banner->web_image) }}" alt="Web Image" width="150" class="mb-2"><br>
                @endif
                <input type="file" name="web_image" id="web_image" class="form-control">
            </div>

            <div class="mb-3">
                <label for="mobile_image" class="form-label">Mobile Image</label><br>
                @if ($banner->mobile_image)
                    <img src="{{ asset('storage/'.$banner->mobile_image) }}" alt="Mobile Image" width="120" class="mb-2"><br>
                @endif
                <input type="file" name="mobile_image" id="mobile_image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" {{ $banner->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$banner->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('banner.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
