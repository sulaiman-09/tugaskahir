@extends('layouts.app')

@section('title', 'Product (Synced)')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Products (Local DB)</h3>
            <a href="{{ route('product.remote') }}" class="btn btn-outline-secondary btn-sm">View Live API</a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Speed</th>
                                <th>Price</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $prod)
                                <tr>
                                    <td>{{ $prod->id }}</td>
                                    <td class="fw-semibold">{{ $prod->name }}</td>
                                    <td>{{ $prod->category->name ?? '-' }}</td>
                                    <td>{{ $prod->speed ?? '-' }}</td>
                                    <td>
                                        @if (!is_null($prod->price))
                                            Rp {{ number_format($prod->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($prod->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data (jalankan
                                        hospitality:sync)</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
