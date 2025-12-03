@php $hideCancel = $hideCancel ?? false; @endphp

{{-- Error box untuk validasi AJAX --}}
<div class="alert alert-danger d-none" data-error-box></div>

<form action="{{ route('news.update', $news->news_id) }}" method="POST" enctype="multipart/form-data"
    class="news-edit-form">
    @csrf
    @method('PUT')

    {{-- News Title --}}
    <div class="mb-4">
        <label class="form-label fw-bold small text-dark">News Title <span class="text-danger">*</span></label>
        <input type="text" name="news_title" id="news_title" class="form-control rounded-3 shadow-sm border-0 bg-white"
            value="{{ old('news_title', $news->news_title) }}" placeholder="Enter news title" required>
    </div>

    {{-- News Content --}}
    <div class="mb-4">
        <label class="form-label fw-bold small text-dark">News Content <span class="text-danger">*</span></label>
        <textarea name="news_content" id="news_content" class="form-control rounded-3 shadow-sm border-0 bg-white"
            rows="6" placeholder="Enter news content" required>{{ old('news_content', $news->news_content) }}</textarea>
    </div>

    {{-- Web Image --}}
    <div class="mb-4">
        <label class="form-label fw-bold small text-dark">Web Image</label>
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
        <label class="form-label fw-bold small text-dark">App Image</label>
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
        <label class="form-label fw-bold small text-dark">Image Caption</label>
        <input type="text" name="news_image_caption" id="news_image_caption"
            class="form-control rounded-3 shadow-sm border-0 bg-white"
            value="{{ old('news_image_caption', $news->news_image_caption) }}" placeholder="Enter image caption">
    </div>

    {{-- Buttons --}}
    <div class="modal-footer sticky-bottom bg-white" style="z-index: 2; border-top: 1px solid #f1f5f9;">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
    </div>
</form>
