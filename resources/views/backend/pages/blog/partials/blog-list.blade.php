<table class="table align-middle table-nowrap table-striped-columns mb-0">
    <thead class="table-light">
        <tr>
            <th scope="col" style="width: 46px;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cardtableCheck">
                    <label class="form-check-label" for="cardtableCheck"></label>
                </div>
            </th>
            <th scope="col">Sr. No. </th>
            <th scope="col" style="width: 80px;">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Slug</th>
            <th scope="col">Published Date</th>
            <th scope="col">Status</th>
            <th scope="col" style="width: 160px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($blogs as $blog)
        <tr>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $blog->id }}" id="cardtableCheck{{ $blog->id }}">
                    <label class="form-check-label" for="cardtableCheck{{ $blog->id }}"></label>
                </div>
            </td>
            <td>{{ $blogs->firstItem() + $loop->index }}</td>
            <td>
                @if($blog->main_image && \Illuminate\Support\Facades\Storage::disk('public')->exists('images/blog/' . $blog->main_image))
                <img src="{{ asset('storage/images/blog/' . $blog->main_image) }}"
                    alt="{{ $blog->title }}"
                    width="50" height="50"
                    style="object-fit:cover;border-radius:6px;">
                @else
                <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                <a href="{{ route('manage-blog.edit', $blog->id) }}" class="fw-medium">
                    {{ Str::limit($blog->title, 30) }}
                </a>
            </td>
            <td class="text-muted">{{ $blog->slug }}</td>
            <td>{{ $blog->published_at ? $blog->published_at->format('d M, Y') : '—' }}</td>
            <td>
                @if($blog->status === 'published')
                <span class="badge bg-success">Published</span>
                @else
                <span class="badge bg-secondary">Draft</span>
                @endif
            </td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('manage-blog.edit', $blog->id) }}" class="btn btn-sm btn-light">
                        <i class="ri-pencil-line align-bottom"></i> Edit
                    </a>
                    <form action="{{ route('manage-blog.destroy', $blog->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-soft-danger show_confirm" data-name="{{ $blog->title }}">
                            <i class="ri-delete-bin-line align-bottom"></i> Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center py-4 text-muted">No blogs found. Click "Add New Blog" to create one.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-3">
    {{ $blogs->links() }}
</div>