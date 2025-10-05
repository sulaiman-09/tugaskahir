@extends('layouts.app')

@section('title', 'Setting Content')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4 text-primary">Setting Content</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div class="input-group w-25">
                    <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>

            <div class="table-responsive">
                <table id="contentTable" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contents as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->order }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="{{ $item->id }}"
                                           {{ $item->status ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('settings-content.edit', $item->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <select class="form-select form-select-sm" style="width: 80px;">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <small class="ms-2">Records per page</small>
                </div>
                <div>
                    {{ $contents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS: Toggle status --}}
<script>
document.querySelectorAll('.toggle-status').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        fetch(`/settings-content/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
        });
    });
});
</script>
@endsection
