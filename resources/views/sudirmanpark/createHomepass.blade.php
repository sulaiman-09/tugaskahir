@extends('layouts.app')

@section('title', 'Add Homepass - Sudirman Park')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h1 class="page-title mb-0">Add Homepass - Sudirman Park</h1>
        <a href="{{ route('sudirmanpark.alamat') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card-body bg-light-subtle p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('sudirmanpark.storeHomepass') }}" method="POST">
            @csrf

            {{-- Tower --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Tower <span
                        class="text-danger">*</span>
                </h6>
                <input type="text" name="tower" id="tower"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Example: A, B, C" required>
            </div>

            {{-- Floor --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Floor <span
                        class="text-danger">*</span></h6>
                <input type="text" name="floor" id="floor"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Example: 01, 02, 07, 15"
                    required>
            </div>

            {{-- Unit --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Unit <span class="text-danger">*</span>
                </h6>
                <input type="text" name="unit" id="unit"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Example: AA, BB, 01, 02"
                    required>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <h6 class="fw-semibold text-primary border-start border-3 ps-2 mb-2">Status</h6>
                <select name="status" id="status" class="form-select rounded-3 shadow-sm border-0 bg-white">
                    <option value="Active" selected>Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            {{-- Save Button --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                    <i class="bi bi-save me-1"></i> Save
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sudirmanpark.css') }}">
@endpush
