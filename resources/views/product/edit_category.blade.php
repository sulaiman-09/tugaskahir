@extends('layouts.app')

@section('title', 'Edit Product Category')

@section('content')
    <div class="container py-4">
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

    {{-- Style Tambahan --}}
    <style>
        body {
            background-color: #f8fafc !important;
        }

        .card {
            background: #ffffff;
        }

        h6 {
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #aacbff !important;
            box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
        }

        .btn-primary {
            background-color: #0d6efd !important;
            border: none !important;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f3f5 !important;
        }

        .form-label.text-primary {
            color: #0d6efd !important;
            font-weight: 600;
        }
    </style>
@endsection
