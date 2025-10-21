@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bold text-dark">Add Banner</h3>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Banner Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="web_image" class="form-label">Web Image</label>
                <input type="file" name="web_image" id="web_image" class="form-control">
            </div>

            <div class="mb-3">
                <label for="mobile_image" class="form-label">Mobile Image</label>
                <input type="file" name="mobile_image" id="mobile_image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('banner.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
