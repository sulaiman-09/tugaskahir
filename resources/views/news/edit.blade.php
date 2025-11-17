@extends('layouts.app')

@section('title', 'Edit News')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Edit News</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('news.update', $news->news_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- News Title --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-primary">News Title <span
                                class="text-danger">*</span></label>
                        <input type="text" name="news_title" id="news_title"
                            class="form-control rounded-3 shadow-sm border-0 bg-white"
                            value="{{ old('news_title', $news->news_title) }}" placeholder="Enter news title" required>
                    </div>

                    {{-- News Content --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-primary">News Content <span
                                class="text-danger">*</span></label>
                        <textarea name="news_content" id="news_content" class="form-control rounded-3 shadow-sm border-0 bg-white"
                            rows="6" placeholder="Enter news content" required>{{ old('news_content', $news->news_content) }}</textarea>
                    </div>

                    {{-- Web Image --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-primary">Web Image</label>
                        @if ($news->news_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($news->news_image) }}" alt="News Image" width="120"
                                    class="rounded border shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="news_image" id="news_image"
                            class="form-control rounded-3 shadow-sm border-0 bg-white">
                        <small class="text-muted">Leave empty if not replacing image.</small>
                    </div>

                    {{-- App Image --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-primary">App Image</label>
                        @if ($news->news_image_app)
                            <div class="mb-2">
                                <img src="{{ Storage::url($news->news_image_app) }}" alt="App Image" width="120"
                                    class="rounded border shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="news_image_app" id="news_image_app"
                            class="form-control rounded-3 shadow-sm border-0 bg-white">
                        <small class="text-muted">Leave empty if not replacing app image.</small>
                    </div>

                    {{-- Caption --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-primary">Image Caption</label>
                        <input type="text" name="news_image_caption" id="news_image_caption"
                            class="form-control rounded-3 shadow-sm border-0 bg-white"
                            value="{{ old('news_image_caption', $news->news_image_caption) }}"
                            placeholder="Enter image caption">
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="fa fa-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            <i class="fa fa-save me-1"></i> Update News
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Style --}}
    <style>
        body {
            background-color: #f8fafc !important;
        }

        .card {
            background: #ffffff;
        }

        .form-control:focus {
            border-color: #aacbff !important;
            box-shadow: 0 0 5px rgba(99, 162, 255, 0.35) !important;
        }

        .btn-primary {
            background-color: #0d6efd !important;
            border: none !important;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f3f5 !important;
        }

        label {
            color: #0d6efd;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#news_content',
            height: 400,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code fullscreen help',
            branding: false,
            promotion: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    </script>
@endpush
