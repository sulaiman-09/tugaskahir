@extends('layouts.app')

@section('title', 'Edit Product Category')

@section('content')
    <div class="container py-4 product-category-page">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Edit Product Category</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">

                {{-- Alert Success --}}
                @if (session('success'))
                    <div class="alert alert-success rounded-3 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                @include('product.partials.category-form', ['category' => $category])
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush
