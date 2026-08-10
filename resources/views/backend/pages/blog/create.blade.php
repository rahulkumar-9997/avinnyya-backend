@extends('backend.layouts.master')
@section('title','Create Blog')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Create Blog</h4>
                        <div class="flex-shrink-0">
                            <div class="form-check form-switch form-switch-right form-switch-md">
                                <a href="{{ route('manage-blog.index') }}" class="btn btn-info custom-toggle active">
                                    <i class="ri-arrow-left-line align-bottom me-1"></i>
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manage-blog.store') }}" method="POST" enctype="multipart/form-data" id="blogCreateForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title *</label>
                                        <input
                                            type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            id="title"
                                            name="title"
                                            placeholder="Enter blog title"
                                            value="{{ old('title') }}">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="slug" class="form-label">Slug *</label>
                                        <input
                                            type="text"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            id="slug"
                                            name="slug"
                                            placeholder="Auto-generated from title if left blank"
                                            value="{{ old('slug') }}">
                                        @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="main_image" class="form-label">Main Image *</label>
                                        <input
                                            type="file"
                                            class="form-control @error('main_image') is-invalid @enderror"
                                            id="main_image"
                                            name="main_image"
                                            accept="image/png, image/jpeg, image/webp">
                                        @error('main_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <img id="mainImagePreview" class="mt-2 d-none" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="page_image" class="form-label">Page Image</label>
                                        <input
                                            type="file"
                                            class="form-control @error('page_image') is-invalid @enderror"
                                            id="page_image"
                                            name="page_image"
                                            accept="image/png, image/jpeg, image/webp">
                                        @error('page_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <img id="pageImagePreview" class="mt-2 d-none" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="blog_status" name="status">
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="published_at" class="form-label">Publish Date</label>
                                        <input
                                            type="datetime-local"
                                            class="form-control @error('published_at') is-invalid @enderror"
                                            id="published_at"
                                            name="published_at"
                                            value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                        @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="short_desc" class="form-label">Short Description</label>
                                        <textarea
                                            class="form-control @error('short_desc') is-invalid @enderror"
                                            id="short_desc"
                                            name="short_desc"
                                            rows="2"
                                            placeholder="Short summary shown in listings">{{ old('short_desc') }}</textarea>
                                        @error('short_desc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="short_desc" class="form-label">Reading Time</label>
                                        <input type="text"
                                            name="reading_time"
                                            class="form-control"
                                            placeholder="e.g. 5 min read"
                                            value="{{ old('reading_time', $blog->reading_time ?? '') }}">

                                        <small class="text-muted">
                                            Leave blank to calculate automatically from content.
                                        </small>
                                        @error('reading_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content *</label>
                                        <textarea
                                            class="form-control ckeditorUpdate4 @error('content') is-invalid @enderror"
                                            id="content"
                                            name="content"
                                            rows="6"
                                            placeholder="Main blog content">{{ old('content') }}</textarea>
                                        @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input
                                            type="text"
                                            class="form-control @error('meta_title') is-invalid @enderror"
                                            id="meta_title"
                                            name="meta_title"
                                            value="{{ old('meta_title') }}">
                                        @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <input
                                            type="text"
                                            class="form-control @error('meta_description') is-invalid @enderror"
                                            id="meta_description"
                                            name="meta_description"
                                            value="{{ old('meta_description') }}">
                                        @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Paragraphs repeater -->
                                <div class="col-lg-12" style="display: none;">
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Paragraphs</h5>
                                        <button type="button" class="btn btn-sm btn-success" id="addParagraphBtn">
                                            <i class="ri-add-line align-middle"></i> Add Paragraph
                                        </button>
                                    </div>
                                    <div id="paragraphsWrapper"></div>
                                    <template id="paragraphTemplate">
                                        <div class="paragraph-row card border mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-semibold paragraph-index-label">Paragraph #__INDEX_DISPLAY__</span>
                                                    <button type="button" class="btn btn-sm btn-soft-danger remove-paragraph-btn">
                                                        <i class="ri-delete-bin-line align-middle"></i> Remove
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Paragraph Title</label>
                                                            <input type="text" class="form-control" name="paragraphs[__INDEX__][title]" placeholder="Paragraph title">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Paragraph Image</label>
                                                            <input type="file" class="form-control" name="paragraphs[__INDEX__][image]" accept="image/png, image/jpeg, image/webp">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-2">
                                                            <label class="form-label">Paragraph Content</label>
                                                            <textarea
                                                                class="form-control ckeditorUpdate4"
                                                                id="paragraph_content___INDEX__"
                                                                rows="3"
                                                                name="paragraphs[__INDEX__][content]"
                                                                placeholder="Paragraph content"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="col-lg-12">
                                    <hr class="my-2">
                                    <div class="mb-3">
                                        <label for="images" class="form-label">Gallery Images (select multiple)</label>
                                        <input
                                            type="file"
                                            class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                            id="images"
                                            name="images[]"
                                            accept="image/png, image/jpeg, image/webp"
                                            multiple>
                                        @error('images')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary" id="submitBlogBtn">Save Blog</button>
                                        <a href="{{ route('manage-blog.index') }}" class="btn btn-soft-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('backend/assets/ckeditor-4/ckeditor.js') }}?v={{ env('ASSET_VERSION', '1.0') }}"></script>

<script>
    window.csrfToken = "{{ csrf_token() }}";
    window.CKEDITOR_ROUTES = {
        upload: "{{ route('ckeditor.upload') }}",
        imagelist: "{{ route('ckeditor.images') }}",
        delete: "{{ route('ckeditor.delete') }}"
    };
</script>
<script src="{{ asset('backend/assets/ckeditor-4/ckeditor-r-create-config.js') }}?v={{ env('ASSET_VERSION', '1.0') }}"></script>
<script>
    (function() {
        var paragraphIndex = 0;
        function addParagraph() {
            var templateHtml = document.getElementById('paragraphTemplate').innerHTML;
            var indexForDisplay = paragraphIndex + 1;
            var html = templateHtml
                .split('__INDEX__').join(paragraphIndex)
                .split('__INDEX_DISPLAY__').join(indexForDisplay);
            var wrapper = document.getElementById('paragraphsWrapper');
            var div = document.createElement('div');
            div.innerHTML = html.trim();
            var row = div.firstChild;
            wrapper.appendChild(row);
            if (window.initCkeditors) {
                window.initCkeditors(row);
            }
            paragraphIndex++;
        }
        function renumberParagraphs() {
            var rows = document.querySelectorAll('#paragraphsWrapper .paragraph-row');
            rows.forEach(function(row, i) {
                var label = row.querySelector('.paragraph-index-label');
                if (label) {
                    label.textContent = 'Paragraph #' + (i + 1);
                }
            });
        }
        document.getElementById('addParagraphBtn').addEventListener('click', addParagraph);
        document.getElementById('paragraphsWrapper').addEventListener('click', function(e) {
            var btn = e.target.closest('.remove-paragraph-btn');
            if (!btn) return;
            var row = btn.closest('.paragraph-row');
            if (row) {
                var textarea = row.querySelector('.ckeditorUpdate4');
                if (window.destroyCkeditor) {
                    window.destroyCkeditor(textarea);
                }
                row.remove();
                renumberParagraphs();
            }
        });
        addParagraph();
        function bindSinglePreview(inputId, previewId) {
            var input = document.getElementById(inputId);
            var preview = document.getElementById(previewId);
            input.addEventListener('change', function() {
                if (!input.files || !input.files[0]) {
                    preview.classList.add('d-none');
                    return;
                }
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            });
        }
        bindSinglePreview('main_image', 'mainImagePreview');
        bindSinglePreview('page_image', 'pageImagePreview');
        document.getElementById('images').addEventListener('change', function(e) {
            var galleryPreview = document.getElementById('galleryPreview');
            galleryPreview.innerHTML = '';
            Array.from(e.target.files).forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    var img = document.createElement('img');
                    img.src = ev.target.result;
                    img.width = 80;
                    img.height = 80;
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '6px';
                    galleryPreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        document.getElementById('blogCreateForm').addEventListener('submit', function() {
            var btn = document.getElementById('submitBlogBtn');
            if (btn.disabled) return false;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
        });
    })();
    document.addEventListener('DOMContentLoaded', function() {
        var titleField = document.getElementById('title');
        var slugField = document.getElementById('slug');
        var slugManuallyEdited = false;
        slugField.addEventListener('input', function() {
            slugManuallyEdited = true;
        });

        function generateSlug(value) {
            return value
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
        titleField.addEventListener('input', function() {
            if (!slugManuallyEdited) {
                slugField.value = generateSlug(this.value);
            }
        });
        titleField.addEventListener('paste', function() {
            setTimeout(function() {
                if (!slugManuallyEdited) {
                    slugField.value = generateSlug(titleField.value);
                }
            }, 0);
        });
    });
</script>
@endpush