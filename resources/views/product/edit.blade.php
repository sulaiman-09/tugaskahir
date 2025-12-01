@extends('layouts.app')

@section('content')
    <div class="container py-4 product-form-page">
        <h2 class="mb-4">Edit Product</h2>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('product.partials.product-form', ['product' => $product, 'categories' => $categories])
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush
