@extends('layouts.app')

@section('title', 'Create News')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3 text-dark">Create News</h4>

        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Judul --}}
            <div class="mb-3">
                <label for="news_title" class="form-label fw-semibold">News Title</label>
                <input type="text" name="news_title" id="news_title" class="form-control" 
                       value="{{ old('news_title') }}" placeholder="Enter news title" required>
            </div>

            {{-- Konten --}}
            <div class="mb-3">
                <label for="news_content" class="form-label fw-semibold">News Content</label>
                <textarea name="news_content" id="news_content" class="form-control" rows="6" 
                          placeholder="Enter news content" required>{{ old('news_content') }}</textarea>
            </div>

            {{-- Gambar Utama --}}
            <div class="mb-3">
                <label for="news_image" class="form-label fw-semibold">News Image</label>
                <input type="file" name="news_image" id="news_image" class="form-control">
            </div>

            {{-- Gambar App --}}
            <div class="mb-3">
                <label for="news_image_app" class="form-label fw-semibold">News Image App</label>
                <input type="file" name="news_image_app" id="news_image_app" class="form-control">
            </div>

            {{-- Caption --}}
            <div class="mb-3">
                <label for="news_image_caption" class="form-label fw-semibold">Image Caption</label>
                <input type="text" name="news_image_caption" id="news_image_caption" class="form-control"
                       value="{{ old('news_image_caption') }}" placeholder="Enter image caption">
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save me-1"></i> Create News
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    label {
        color: #333;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#news_content',
        height: 400,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code fullscreen help',
        branding: false,
        promotion: false,
    });
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#news_content',
        height: 400,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code fullscreen help',
        branding: false,
        promotion: false,
        setup: function (editor) {
            // Sinkronisasi otomatis ke textarea setiap kali user ubah isi
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Pastikan juga sebelum form disubmit, isi TinyMCE dikirim
    document.querySelector('form').addEventListener('submit', function () {
        tinymce.triggerSave();
    });
</script>
@endpush


