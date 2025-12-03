@php
    $hideCancel = $hideCancel ?? false;
    $category = $category ?? new \App\Models\ProductCategory();
    $isEdit = $category && $category->exists;
    $formAction =
        $formAction ?? ($isEdit ? route('product.category.update', $category->id) : route('product.category.store'));
    $method = strtoupper($method ?? ($isEdit ? 'PUT' : 'POST'));
    $submitLabel = $submitLabel ?? ($isEdit ? 'Update Category' : 'Create Category');
@endphp

{{-- Alert Validation (AJAX akan isi di sini) --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ $formAction }}" method="POST" class="category-edit-form">
    @csrf
    @unless (in_array($method, ['GET', 'POST']))
        @method($method)
    @endunless

    <div class="mb-4">
        <div class="row g-3">

            {{-- Category Name --}}
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold text-dark">
                    Category Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="name"
                    class="form-control rounded-3 shadow-sm border-0 bg-white" placeholder="Masukkan nama kategori"
                    value="{{ old('name', $category->name) }}" required>
            </div>

            {{-- Slug --}}
            <div class="col-md-6">
                <label for="slug" class="form-label fw-bold text-dark">
                    Slug <span class="text-danger">*</span>
                </label>
                <input type="text" name="slug" id="slug"
                    class="form-control rounded-3 shadow-sm border-0 bg-white"
                    placeholder="Masukkan slug (otomatis atau manual)" value="{{ old('slug', $category->slug) }}"
                    required>
            </div>

            {{-- Short Description --}}
            <div class="col-md-12">
                <label for="short_description" class="form-label fw-bold text-dark">
                    Short Description
                </label>
                <textarea name="short_description" id="short_description" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    rows="3" required placeholder="Tuliskan deskripsi singkat kategori">{{ old('short_description', $category->short_description) }}</textarea>
            </div>

            {{-- Long Description / Benefits --}}
            <div class="col-md-12">
                <label for="long_description" class="form-label fw-bold text-dark">
                    Benefits (Long Description)
                </label>
                <textarea name="long_description" id="long_description" class="form-control rounded-3 shadow-sm border-0 bg-white"
                    rows="6" placeholder="Gunakan baris baru untuk setiap poin manfaat...">{{ old('long_description', $category->long_description) }}</textarea>
                <small class="text-muted">
                    Gunakan baris baru untuk setiap poin benefit.
                </small>
            </div>

            @unless ($isEdit)
                <div class="col-md-12">
                    <label for="benefit" class="form-label fw-bold text-dark">
                        Benefit
                    </label>
                    <textarea name="benefit" id="benefit" class="form-control rounded-3 shadow-sm border-0 bg-white" rows="3"
                        placeholder="Tuliskan manfaat atau keunggulan kategori">{{ old('benefit') }}</textarea>
                    <small class="text-muted">Pisahkan baris untuk setiap benefit.</small>
                </div>
            @endunless

            {{-- Show Price --}}
            <div class="col-md-12">
                <div class="form-check mt-3 ps-1" style="display: flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="show_price" value="0">
                    <input type="checkbox" name="show_price" id="showPrice" value="1"
                        {{ old('show_price', $category->show_price ?? 1) ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer; margin-top: 2px;">

                    <label for="show_price" class="fw-semibold text-dark"
                        style="font-size: 15px; margin: 0; cursor: pointer;">
                        Show Price by Default
                    </label>
                </div>
            </div>

        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div
        class="d-flex {{ $hideCancel ? 'justify-content-end' : 'justify-content-between' }} align-items-center border-top pt-3 mt-4">
        @unless ($hideCancel)
            <a href="{{ route('product.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Cancel
            </a>
        @endunless
        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
            <i class="bi bi-save2 me-1"></i> {{ $submitLabel }}
        </button>
    </div>
</form>
