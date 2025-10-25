@extends('layouts.app')

@section('title', 'Settings Content')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3 text-dark">Settings Content</h4>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Name</th>
                        <th>Type ID</th>
                        <th>Order</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $index => $content)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $content->title }}</td>
                            <td>{{ $content->name }}</td>
                            <td>{{ $content->content_type_id }}</td>
                            <td>{{ $content->order }}</td>

                            {{-- ✅ Gambar dari database --}}
                            <td>
    @if($content->image_path)
        <img src="{{ asset('storage/' . $content->image_path) }}" 
             alt="Image" width="80" class="rounded shadow-sm">
    @else
        <span class="text-muted">No Image</span>
    @endif
</td>

                            <td>
                                @if($content->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('settings-content.edit', $content->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if($contents->isEmpty())
                        <tr>
                            <td colspan="8">No data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
