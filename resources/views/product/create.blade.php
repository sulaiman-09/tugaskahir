@extends('layouts.app')

@section('title', 'Create New Product')

@section('content')
    <div class="container py-4 product-form-page">
        <h2 class="mb-4">Add New Product</h2>

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

        @include('product.partials.product-form', [
            'product' => new \App\Models\Product(),
            'categories' => $categories,
            'formAction' => route('product.store'),
            'method' => 'POST',
            'submitLabel' => 'Create Product',
        ])
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush
