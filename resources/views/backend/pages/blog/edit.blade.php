@extends('backend.layouts.master')
@section('title','Edit Blog')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Edit Blog</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('manage-blog.index') }}" class="btn btn-info custom-toggle active">
                                <i class="ri-arrow-left-line align-bottom me-1"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manage-blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data" id="blogEditForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $blog->title) }}">
                                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="slug" class="form-label">Slug *</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" value="{{ old('slug', $blog->slug) }}">
                                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="main_image" class="form-label">Main Image</label>
                                        <input type="file" class="form-control @error('main_image') is-invalid @enderror"
                                            id="main_image" name="main_image" accept="image/png, image/jpeg, image/webp">
                                        @error('main_image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                        @if($blog->main_image_url)
                                        <div class="mt-2">
                                            <small class="text-muted d-block mb-1">Current image:</small>
                                            <img src="{{ $blog->main_image_url }}" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                        </div>
                                        @endif
                                        <img id="mainImagePreview" class="mt-2 d-none" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="page_image" class="form-label">Page Image</label>
                                        <input type="file" class="form-control @error('page_image') is-invalid @enderror"
                                            id="page_image" name="page_image" accept="image/png, image/jpeg, image/webp">
                                        @error('page_image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                        @if($blog->page_image_url)
                                        <div class="mt-2">
                                            <small class="text-muted d-block mb-1">Current image:</small>
                                            <img src="{{ $blog->page_image_url }}" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" id="removePageImage" name="remove_page_image" value="1">
                                                <label class="form-check-label text-danger" for="removePageImage">Remove page image</label>
                                            </div>
                                        </div>
                                        @endif
                                        <img id="pageImagePreview" class="mt-2 d-none" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="blog_status" name="status">
                                            <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="published_at" class="form-label">Publish Date</label>
                                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                            id="published_at" name="published_at"
                                            value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d\TH:i')) }}">
                                        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="short_desc" class="form-label">Short Description</label>
                                        <textarea class="form-control @error('short_desc') is-invalid @enderror"
                                            id="short_desc" name="short_desc" rows="2">{{ old('short_desc', $blog->short_desc) }}</textarea>
                                        @error('short_desc')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                        <textarea class="form-control ckeditorUpdate4 @error('content') is-invalid @enderror"
                                            id="content" name="content" rows="6">{{ old('content', $blog->content) }}</textarea>
                                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                            id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}">
                                        @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <input type="text" class="form-control @error('meta_description') is-invalid @enderror"
                                            id="meta_description" name="meta_description" value="{{ old('meta_description', $blog->meta_description) }}">
                                        @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <!-- Paragraphs repeater (hidden, same as create form) -->
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
                                                <input type="hidden" name="paragraphs[__INDEX__][id]" class="paragraph-id-field" value="__PARAGRAPH_ID__">
                                                <input type="hidden" name="paragraphs[__INDEX__][existing_image]" class="paragraph-existing-image-field" value="__EXISTING_IMAGE__">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Paragraph Title</label>
                                                            <input type="text" class="form-control paragraph-title-field" name="paragraphs[__INDEX__][title]" value="__TITLE__" placeholder="Paragraph title">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Paragraph Image</label>
                                                            <input type="file" class="form-control" name="paragraphs[__INDEX__][image]" accept="image/png, image/jpeg, image/webp">
                                                            <div class="paragraph-existing-image-preview mt-1"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-2">
                                                            <label class="form-label">Paragraph Content</label>
                                                            <textarea class="form-control ckeditorUpdate4 paragraph-content-field"
                                                                id="paragraph_content___INDEX__" rows="3"
                                                                name="paragraphs[__INDEX__][content]" placeholder="Paragraph content"></textarea>
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
                                        <label class="form-label">Existing Gallery Images</label>
                                        <div id="existingGalleryWrapper" class="d-flex flex-wrap gap-2 mb-3">
                                            @foreach($blog->images as $image)
                                            @if($image->image)
                                            <div class="position-relative gallery-existing-item" data-image-id="{{ $image->id }}">
                                                <img src="{{ asset('storage/images/blog/gallery/' . $image->image) }}" width="80" height="80" style="object-fit:cover;border-radius:6px;">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 remove-existing-gallery-btn"
                                                    style="width:22px;height:22px;line-height:1;"
                                                    data-url="{{ route('manage-blog.gallery-image.destroy', $image->id) }}">&times;</button>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>

                                        <label for="images" class="form-label">Add More Gallery Images</label>
                                        <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                            id="images" name="images[]" accept="image/png, image/jpeg, image/webp" multiple>
                                        @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                        <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary" id="submitBlogBtn">Update Blog</button>
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
    window.EXISTING_PARAGRAPHS = @json($existingParagraphs);
    
</script>
<script src="{{ asset('backend/assets/ckeditor-4/ckeditor-r-create-config.js') }}?v={{ env('ASSET_VERSION', '1.0') }}"></script>
<script>
    (function() {
        var paragraphIndex = 0;

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function addParagraph(data) {
            data = data || {};
            var templateHtml = document.getElementById('paragraphTemplate').innerHTML;
            var indexForDisplay = paragraphIndex + 1;
            var html = templateHtml
                .split('__INDEX__').join(paragraphIndex)
                .split('__INDEX_DISPLAY__').join(indexForDisplay)
                .split('__PARAGRAPH_ID__').join(data.id || '')
                .split('__EXISTING_IMAGE__').join(data.existing_image || '')
                .split('__TITLE__').join(escapeHtml(data.title));

            var wrapper = document.getElementById('paragraphsWrapper');
            var div = document.createElement('div');
            div.innerHTML = html.trim();
            var row = div.firstChild;

            var contentField = row.querySelector('.paragraph-content-field');
            if (contentField && data.content) {
                contentField.value = data.content;
            }
            if (data.image_url) {
                var previewSlot = row.querySelector('.paragraph-existing-image-preview');
                if (previewSlot) {
                    previewSlot.innerHTML = '<img src="' + data.image_url + '" width="60" height="60" style="object-fit:cover;border-radius:6px;">';
                }
            }

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
                if (label) label.textContent = 'Paragraph #' + (i + 1);
            });
        }

        document.getElementById('addParagraphBtn').addEventListener('click', function() {
            addParagraph({});
        });

        document.getElementById('paragraphsWrapper').addEventListener('click', function(e) {
            var btn = e.target.closest('.remove-paragraph-btn');
            if (!btn) return;
            var row = btn.closest('.paragraph-row');
            if (row) {
                var textarea = row.querySelector('.ckeditorUpdate4');
                if (window.destroyCkeditor) window.destroyCkeditor(textarea);
                row.remove();
                renumberParagraphs();
            }
        });

        // Render existing paragraphs on page load; fall back to one empty row if none.
        if (window.EXISTING_PARAGRAPHS && window.EXISTING_PARAGRAPHS.length) {
            window.EXISTING_PARAGRAPHS.forEach(function(p) {
                addParagraph(p);
            });
        } else {
            addParagraph({});
        }

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

        // Delete existing gallery image via AJAX (immediate, since it's already saved)
        document.getElementById('existingGalleryWrapper').addEventListener('click', function(e) {
            var btn = e.target.closest('.remove-existing-gallery-btn');
            if (!btn) return;
            if (!confirm('Delete this gallery image? This cannot be undone.')) return;

            fetch(btn.dataset.url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function() {
                    btn.closest('.gallery-existing-item').remove();
                })
                .catch(function() {
                    alert('Failed to delete image. Please try again.');
                });
        });

        document.getElementById('blogEditForm').addEventListener('submit', function() {
            var btn = document.getElementById('submitBlogBtn');
            if (btn.disabled) return false;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
        });
    })();

    document.addEventListener('DOMContentLoaded', function() {
        var titleField = document.getElementById('title');
        var slugField = document.getElementById('slug');
        var slugManuallyEdited = true; // slug already exists on edit — don't auto-overwrite unless user clears it

        slugField.addEventListener('input', function() {
            slugManuallyEdited = slugField.value.trim() !== '';
        });

        function generateSlug(value, maxLength) {
            maxLength = maxLength || 100;
            var slug = value.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            if (slug.length > maxLength) {
                slug = slug.substring(0, maxLength);
                var lastHyphen = slug.lastIndexOf('-');
                if (lastHyphen > 0) slug = slug.substring(0, lastHyphen);
            }
            return slug;
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