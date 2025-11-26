@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Alert Validation (AJAX akan isi di sini) --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data"
    class="product-edit-form">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label fw-semibold">Product Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Speed</label>
        <input type="text" name="speed" class="form-control" value="{{ old('speed', $product->speed) }}"
            placeholder="e.g. 30 Mbps" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="3" required>{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Category</label>
        <select name="product_category_id" class="form-select" required>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ $cat->id == $product->product_category_id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Price</label>
        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}"
            placeholder="e.g. 150000">
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Web Image</label><br>
        @if ($product->web_image)
            <img src="{{ asset('storage/' . $product->web_image) }}" width="100" class="mb-2 rounded shadow-sm border">
        @endif
        <input type="file" name="web_image" class="form-control">
        <small class="text-muted">Recommended: 800x600px</small>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Apps Image</label>
        <div class="mb-2">
            @if ($product->path_apps)
                <img src="{{ asset('storage/' . $product->path_apps) }}" width="100" class="rounded shadow-sm border">
            @endif
        </div>
        <input type="file" name="apps_image" class="form-control">
        <small class="text-muted">Recommended: 800x600px</small>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        @unless($hideCancel)
            <a href="{{ route('product.index') }}" class="btn btn-secondary ms-2">
                <i class="fa fa-arrow-left me-1"></i> Cancel
            </a>
        @endunless
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Update Product
        </button>
    </div>
</form>
