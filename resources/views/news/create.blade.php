@extends('layouts.app')

@section('title', 'Create News')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Add New News</h5>
            </div>

            {{-- Body --}}
            <div class="card-body bg-light-subtle p-4">
                <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-primary">News Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="news_title" id="news_title"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white"
                                    value="{{ old('news_title') }}" placeholder="Enter news title" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-primary">News Content <span
                                        class="text-danger">*</span></label>
                                <textarea name="news_content" id="news_content" class="form-control rounded-3 shadow-sm border-0 bg-white"
                                    rows="6" placeholder="Enter news content" required>{{ old('news_content') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-primary">Web Image</label>
                                <input type="file" name="news_image" id="news_image"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-primary">App Image</label>
                                <input type="file" name="news_image_app" id="news_image_app"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-primary">Image Caption</label>
                                <input type="text" name="news_image_caption" id="news_image_caption"
                                    class="form-control rounded-3 shadow-sm border-0 bg-white"
                                    value="{{ old('news_image_caption') }}" placeholder="Enter image caption">
                            </div>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">
                            Create News
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- STYLE TAMBAHAN --}}
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

        h6 {
            font-size: 0.95rem;
        }
    </style>

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
@endsection
