@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bold">Data Banner</h3>

    <!-- Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Banner
                    </button>
                </div>

                <!-- Search -->
                <form action="{{ route('banner.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Web Image</th>
                            <th>Mobile Image</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                <td class="fw-semibold">{{ $banner['name'] }}</td>
                                <td>
                                    <img src="{{ $banner['web_image'] }}" alt="web image" width="150" class="rounded shadow-sm">
                                </td>
                                <td>
                                    <img src="{{ $banner['mobile_image'] }}" alt="mobile image" width="100" class="rounded shadow-sm">
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" role="switch" id="status{{ $loop->index }}" {{ $banner['status'] ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Placeholder -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <select class="form-select form-select-sm w-auto">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <small class="ms-2">Records per page</small>
                </div>
                <small class="text-muted">Showing 1 to {{ count($banners) }} of {{ count($banners) }} Results</small>
            </div>
        </div>
    </div>
</div>
@endsection
