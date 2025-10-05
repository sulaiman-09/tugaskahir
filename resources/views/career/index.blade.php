@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3 class="mb-3">Career Management</h3>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">List Career</h6>

                <form action="{{ route('career.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary ms-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Education</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($careers as $career)
                            <tr>
                                <td>{{ $career->id }}</td>
                                <td><img src="{{ $career->image }}" alt="Career Image" width="50"
                                        class="rounded"></td>
                                <td>{{ $career->title }}</td>
                                <td>{{ $career->type }}</td>
                                <td>{{ $career->education }}</td>
                                <td>{{ $career->location }}</td>
                                <td>
                                    <span
                                        class="badge bg-success">{{ $career->status }}</span>
                                </td>
                                <td>{{ $career->created_at }}</td>
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
        </div>
    </div>
@endsection
