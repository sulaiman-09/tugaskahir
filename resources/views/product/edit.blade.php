@extends('layouts.app')

@section('content')
    <div class="container py-4">
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
    <style>
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-label {
            color: #0d6efd;
        }
    </style>
@endpush
