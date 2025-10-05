@extends('layouts.app')

@section('title', 'Data Division')

@section('content')
<div class="container">
    <h2>Data Division</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('division.create') }}" class="btn btn-primary">+ Add Division</a>
        <form action="{{ route('division.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search...">
        </form>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Customer Leads</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($divisions as $division)
                <tr>
                    <td>{{ $division->id }}</td>
                    <td>{{ $division->name }}</td>
                    <td>{{ $division->description }}</td>
                    <td>
                        <input type="checkbox" disabled {{ $division->status ? 'checked' : '' }}>
                    </td>
                    <td>{{ $division->customer_leads }}</td>
                    <td>{{ $division->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>
                        <a href="{{ route('division.edit', $division->id) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('division.destroy', $division->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $divisions->links() }}
</div>
@endsection
