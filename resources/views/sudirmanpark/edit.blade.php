@extends('layouts.app')

@section('title', 'Edit Customer Sudirman Park')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Data Customer</h1>
</div>

<div class="card shadow-sm p-4">
    <form action="{{ route('sudirmanpark.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- isi form mirip create.blade.php tapi isinya diisi nilai lama -->
        <div class="mb-3">
            <label class="form-label">Nama Customer *</label>
            <input type="text" name="name" value="{{ $customer->name }}" class="form-control" required>
        </div>

        <!-- dan seterusnya... -->
    </form>
</div>
@endsection
