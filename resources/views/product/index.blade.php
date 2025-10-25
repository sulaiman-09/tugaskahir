@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4 border-0">

        {{-- Judul Halaman --}}
        <h4 class="fw-bold mb-3 text-dark">Product Management</h4>


{{-- ======================= --}}
{{-- TABEL 1 : PRODUCT CATEGORY --}}
{{-- ======================= --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark">Product Categories</h6>
            <a href="{{ route('product.category.create') }}" class="btn btn-primary btn-sm">
                + Tambah Category Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Short Description</th>
                        <th>Show Price</th>
                        <th>Benefits</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td class="fw-semibold text-dark">{{ $cat->name }}</td>
                            <td>{{ $cat->slug }}</td>
                            <td class="text-start">{{ $cat->short_description }}</td>

                            {{-- Toggle Show Price --}}
                            <td>
                                <form action="{{ route('product.category.update', $cat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $cat->name }}">
                                    <input type="hidden" name="slug" value="{{ $cat->slug }}">
                                    <input type="hidden" name="short_description" value="{{ $cat->short_description }}">
                                    <input type="hidden" name="long_description" value="{{ $cat->long_description }}">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input type="checkbox" class="form-check-input toggle-switch"
                                               name="show_price"
                                               id="show_price_{{ $cat->id }}"
                                               onchange="this.form.submit()"
                                               {{ $cat->show_price ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </td>

                            {{-- Benefits (ambil dari long_description) --}}
                            <td class="text-start">
                                @if(!empty($cat->long_description))
                                    <ul class="mb-0 list-unstyled">
                                        @foreach(preg_split("/\r\n|\n|\r/", trim($cat->long_description)) as $line)
                                            @if(!empty(trim($line)))
                                                <li>{!! $line !!}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted fst-italic">No benefits listed.</span>
                                @endif
                            </td>

                            {{-- Action Buttons --}}
                            <td class="text-nowrap">
                                <a href="{{ route('product.category.edit', $cat->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('product.category.destroy', $cat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin hapus kategori ini?')" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



        {{-- Tombol Tambah Produk --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                + Tambah Product Baru
            </a>
        </div>
        {{-- ======================= --}}
        {{-- TABEL 2 : PRODUCT LIST --}}
        {{-- ======================= --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                {{-- Header dan Search --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Product List</h6>

                    <form action="{{ route('product.index') }}" method="GET" class="d-flex align-items-center" style="max-width: 250px;">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search product..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                {{-- Pesan sukses --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Tabel Produk --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>
                                    <a href="{{ route('product.index', ['sort' => $sort === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-primary">
                                        ID
                                        @if($sort === 'asc')
                                            <i class="fa fa-arrow-up"></i>
                                        @else
                                            <i class="fa fa-arrow-down"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Name</th>
                                <th>Speed</th>
                                <th>Website Image</th>
                                <th>Apps Image</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $prod)
                                <tr>
                                    <td>{{ $prod->id }}</td>
                                    <td>{{ $prod->name }}</td>
                                    <td>{{ $prod->speed }}</td>
                                    <td>
                                        @if($prod->web_image)
                                            <img src="{{ asset('storage/' . $prod->web_image) }}" class="img-thumbnail" width="100">
                                        @endif
                                    </td>
                                    <td>
                                        @if($prod->apps_image)
                                            <img src="{{ asset('storage/' . $prod->apps_image) }}" class="img-thumbnail" width="100">
                                        @endif
                                    </td>
                                    <td>{{ $prod->category->name ?? '-' }}</td>
                                    <td>
                                        @if($prod->show_price)
                                            Rp {{ number_format($prod->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted fst-italic">Hidden</span>
                                        @endif
                                    </td>
                                    <td>{{ $prod->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('product.edit', $prod->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{ route('product.togglePrice', $prod->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($prod->show_price)
                                                <button class="btn btn-sm btn-secondary" title="Hide Price">
                                                    <i class="fa fa-eye-slash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-success" title="Show Price">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            @endif
                                        </form>

                                        <form action="{{ route('product.destroy', $prod->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus produk ini?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted">Belum ada produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
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
    .btn-warning, .btn-danger, .btn-secondary, .btn-success {
        border: none;
    }
    .table-primary {
        background-color: #e3f2fd !important;
        color: #0d6efd;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9fcff;
    }
    .table-hover tbody tr:hover {
        background-color: #e9f4ff !important;
    }
    .card {
        border-radius: 12px;
    }
    .form-switch .form-check-input {
        width: 50px;
        height: 25px;
        cursor: pointer;
    }
    .toggle-switch:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .table-primary {
        background-color: #e3f2fd !important;
        color: #0d6efd;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9fcff;
    }
    .table-hover tbody tr:hover {
        background-color: #e9f4ff !important;
    }
</style>
@endpush
