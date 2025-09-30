@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container mt-4">
    <h1>Edit Customer</h1>
    <form method="POST" action="{{ route('customer.update', $customer['id']) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text" name="name" class="form-control" value="{{ $customer['name'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="phone" class="form-control" value="{{ $customer['phone'] }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $customer['email'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address" class="form-control" rows="2" required>{{ $customer['address'] }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Coverage</label>
            <input type="text" name="coverage" class="form-control" value="{{ $customer['coverage'] }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
