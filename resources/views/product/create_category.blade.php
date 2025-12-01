@extends('layouts.app')

@section('title', 'Tambah Kategori Produk')

@section('content')
    <div class="container py-4 product-category-page">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Add New Product Category</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">

                {{-- Tampilkan Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 shadow-sm">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @include('product.partials.category-form', [
                    'category' => new \App\Models\ProductCategory(),
                    'formAction' => route('product.category.store'),
                    'method' => 'POST',
                    'submitLabel' => 'Create Category',
                ])
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush
