@extends('layouts.app')

@section('title', 'Product API (Hospitality)')

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="fw-bold mb-0">Products (API Hospitality)</h3>
            <a href="{{ route('product.index') }}" class="btn btn-outline-secondary btn-sm">Back to local list</a>
        </div>

        @if ($apiError)
            <div class="alert alert-danger">{{ $apiError }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Category</th>
                                <th>Slug</th>
                                <th>Short Description</th>
                                <th>Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($apiProducts as $item)
                                <tr>
                                    <td>{{ $item['id'] ?? '-' }}</td>
                                    <td>{{ $item['category'] ?? '-' }}</td>
                                    <td>{{ $item['slug'] ?? '-' }}</td>
                                    <td style="max-width: 320px;">{{ $item['short_description'] ?? '-' }}</td>
                                    <td class="text-start">
                                        @if (!empty($item['products']))
                                            <ul class="mb-0 ps-3">
                                                @foreach ($item['products'] as $prod)
                                                    <li>
                                                        <strong>{{ $prod['name'] ?? '-' }}</strong>
                                                        @if (isset($prod['price']))
                                                            — Rp {{ number_format((float) $prod['price'], 0, ',', '.') }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">No products</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No data from API.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
