@extends('layouts.app')

@section('title', 'Edit News')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold mb-3 text-dark">Edit News</h4>

        <form action="{{ route('news.update', $news->news_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div class="mb-3">
                <label for="news_title" class="form-label fw-semibold">News Title</label>
                <input type="text" name="news_title" id="news_title" class="form-control" 
                       value="{{ old('news_title', $news->news_title) }}" required>
            </div>

            {{-- Konten --}}
            <div class="mb-3">
                <label for="news_content" class="form-label fw-semibold">News Content</label>
                <textarea name="news_content" id="news_content" class="form-control" rows="6" required>{{ old('news_content', $news->news_content) }}</textarea>
            </div>

            {{-- Gambar Utama --}}
            <div class="mb-3">
                <label for="news_image" class="form-label fw-semibold">News Image</label>
                <div class="mb-2">
                    @if ($news->news_image)
                        <img src="{{ asset($news->news_image) }}" alt="News Image" width="120" class="rounded border shadow-sm">
                    @endif
                </div>
                <input type="file" name="news_image" id="news_image" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
            </div>

            {{-- Gambar App --}}
            <div class="mb-3">
                <label for="news_image_app" class="form-label fw-semibold">News Image (App)</label>
                <div class="mb-2">
                    @if ($news->news_image_app)
                        <img src="{{ asset($news->news_image_app) }}" alt="App Image" width="120" class="rounded border shadow-sm">
                    @endif
                </div>
                <input type="file" name="news_image_app" id="news_image_app" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar app.</small>
            </div>

            {{-- Caption --}}
            <div class="mb-3">
                <label for="news_image_caption" class="form-label fw-semibold">Image Caption</label>
                <input type="text" name="news_image_caption" id="news_image_caption" class="form-control"
                       value="{{ old('news_image_caption', $news->news_image_caption) }}">
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save me-1"></i> Update
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
