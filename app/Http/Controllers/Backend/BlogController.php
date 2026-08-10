<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use App\Models\Blog;
use App\Models\BlogParagraph;
use App\Models\BlogImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    private function calculateReadingTime($content)
    {
        $content = strip_tags($content ?? '');
        $wordCount = str_word_count($content);
        $minutes = max(1, ceil($wordCount / 200));
        return $minutes . ' min read';
    }

    public function index()
    {
        $blogs = Blog::latest()->paginate(15);
        return view('backend.pages.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('backend.pages.blog.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'short_desc' => 'nullable|string|max:500',
            'reading_time' => 'nullable|string|max:100',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'main_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'page_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',

            'paragraphs' => 'nullable|array',
            'paragraphs.*.title' => 'nullable|string|max:255',
            'paragraphs.*.content' => 'nullable|string',
            'paragraphs.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below and try again.');
        }

        $validated = $validator->validated();
        $singleUploads = [];
        DB::beginTransaction();
        try {
            $baseSlug = $validated['slug'] ?? Str::slug($validated['title']);
            $baseSlug = Str::limit($baseSlug, 100, '');
            $baseSlug = trim($baseSlug, '-'); 

            $slug = $baseSlug;
            $originalSlug = $slug;
            $suffix = 1;
            while (Blog::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $suffix++;
            }

            $mainImage = null;
            if ($request->hasFile('main_image')) {
                $mainImageName = ImageHelper::generateFileName($validated['title'], 'blog-main');
                $mainImage = ImageHelper::uploadSingleImageWebpOnly(
                    $request->file('main_image'),
                    $mainImageName,
                    'blog'
                );
                $singleUploads[] = ['name' => $mainImage, 'folder' => 'blog'];
            }

            $pageImage = null;
            if ($request->hasFile('page_image')) {
                $pageImageName = ImageHelper::generateFileName($validated['title'], 'blog-page');
                $pageImage = ImageHelper::uploadSingleImageWebpOnly(
                    $request->file('page_image'),
                    $pageImageName,
                    'blog'
                );
                $singleUploads[] = ['name' => $pageImage, 'folder' => 'blog'];
            }

            $blog = Blog::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'short_desc' => $validated['short_desc'] ?? null,
                'content' => $validated['content'],
                'reading_time' => !empty($validated['reading_time'])
                ? $validated['reading_time']
                : $this->calculateReadingTime($validated['content']),
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'main_image' => $mainImage,
                'page_image' => $pageImage,
                'status' => $validated['status'],
                'visitor_count' => 0,
                'published_at' => $validated['published_at'] ?? null,
            ]);

            /* Paragraphs - each may have its own optional image. */
            if (!empty($validated['paragraphs'])) {
                foreach ($validated['paragraphs'] as $index => $paragraph) {
                    $paragraphImage = null;

                    if ($request->hasFile("paragraphs.$index.image")) {
                        $imgName = ImageHelper::generateFileName(
                            ($paragraph['title'] ?? $blog->title) . '-p' . $index,
                            'blog-paragraph'
                        );
                        $paragraphImage = ImageHelper::uploadSingleImageWebpOnly(
                            $request->file("paragraphs.$index.image"),
                            $imgName,
                            'blog/paragraphs'
                        );
                        $singleUploads[] = ['name' => $paragraphImage, 'folder' => 'blog/paragraphs'];
                    }

                    $blog->paragraphs()->create([
                        'title' => $paragraph['title'] ?? null,
                        'content' => $paragraph['content'] ?? null,
                        'image' => $paragraphImage,
                        'sort_order' => $index,
                    ]);
                }
            }

            /* Gallery images - multiple files from a single <input multiple>. */
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $imageFile) {
                    $imgName = ImageHelper::generateFileName(
                        $blog->title . '-gallery-' . $index,
                        'blog-gallery'
                    );
                    $galleryImage = ImageHelper::uploadSingleImageWebpOnly(
                        $imageFile,
                        $imgName,
                        'blog/gallery'
                    );
                    $singleUploads[] = ['name' => $galleryImage, 'folder' => 'blog/gallery'];

                    $blog->images()->create([
                        'image' => $galleryImage,
                        'alt_text' => $blog->title,
                        'sort_order' => $index,
                    ]);
                }
            }
            DB::commit();
            Cache::forget('api_blog_list');
            return redirect()->route('manage-blog.index')->with('success', 'Blog created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($singleUploads as $file) {
                ImageHelper::deleteSingleImage($file['name'], $file['folder']);
            }
            Log::error('Blog creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->except(['main_image', 'page_image', 'images', 'paragraphs']))
                ->with('error', 'Something went wrong while creating the blog. Please try again.');
        }
    }

    public function edit(Blog $blog)
    {
        $blog->load(['paragraphs' => function ($q) {
            $q->orderBy('sort_order');
        }, 'images' => function ($q) {
            $q->orderBy('sort_order');
        }]);

        $existingParagraphs = $blog->paragraphs->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'content' => $p->content,
                'image_url' => $p->image_url ?? null,
                'existing_image' => $p->image,
            ];
        })->values();

        return view('backend.pages.blog.edit', compact('blog', 'existingParagraphs'));
    }
    public function update(Request $request, Blog $blog)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'short_desc' => 'nullable|string|max:500',
            'content' => 'required|string',
            'reading_time' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'page_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_page_image' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',

            'paragraphs' => 'nullable|array',
            'paragraphs.*.id' => 'nullable|integer|exists:blog_paragraphs,id',
            'paragraphs.*.title' => 'nullable|string|max:255',
            'paragraphs.*.content' => 'nullable|string',
            'paragraphs.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'paragraphs.*.existing_image' => 'nullable|string',
            'paragraphs.*.remove_image' => 'nullable|boolean',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below and try again.');
        }

        $validated = $validator->validated();
        $newUploads = [];  
        $toDeleteOnSuccess = [];
        DB::beginTransaction();
        try {
            
            $mainImage = $blog->main_image;
            if ($request->hasFile('main_image')) {
                $mainImageName = ImageHelper::generateFileName($validated['title'], 'blog-main');
                $mainImage = ImageHelper::uploadSingleImageWebpOnly(
                    $request->file('main_image'),
                    $mainImageName,
                    'blog'
                );
                $newUploads[] = ['name' => $mainImage, 'folder' => 'blog'];
                if ($blog->main_image) {
                    $toDeleteOnSuccess[] = ['name' => $blog->main_image, 'folder' => 'blog'];
                }
            }
           
            $pageImage = $blog->page_image;
            if ($request->hasFile('page_image')) {
                $pageImageName = ImageHelper::generateFileName($validated['title'], 'blog-page');
                $pageImage = ImageHelper::uploadSingleImageWebpOnly(
                    $request->file('page_image'),
                    $pageImageName,
                    'blog'
                );
                $newUploads[] = ['name' => $pageImage, 'folder' => 'blog'];
                if ($blog->page_image) {
                    $toDeleteOnSuccess[] = ['name' => $blog->page_image, 'folder' => 'blog'];
                }
            } elseif ($request->boolean('remove_page_image')) {
                if ($blog->page_image) {
                    $toDeleteOnSuccess[] = ['name' => $blog->page_image, 'folder' => 'blog'];
                }
                $pageImage = null;
            }

            $blog->update([
                'title' => $validated['title'],
                'short_desc' => $validated['short_desc'] ?? null,
                'content' => $validated['content'],
                'reading_time' => !empty($validated['reading_time'])
                ? $validated['reading_time']
                : $this->calculateReadingTime($validated['content']),
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'main_image' => $mainImage,
                'page_image' => $pageImage,
                'status' => $validated['status'],
                'published_at' => $validated['published_at'] ?? null,
            ]);

            /* Paragraphs: update existing, create new, delete removed */
            $submittedIds = [];
            if (!empty($validated['paragraphs'])) {
                foreach ($validated['paragraphs'] as $index => $paragraph) {
                    $paragraphImage = $paragraph['existing_image'] ?? null;

                    if ($request->hasFile("paragraphs.$index.image")) {
                        $imgName = ImageHelper::generateFileName(
                            ($paragraph['title'] ?? $blog->title) . '-p' . $index,
                            'blog-paragraph'
                        );
                        $newImage = ImageHelper::uploadSingleImageWebpOnly(
                            $request->file("paragraphs.$index.image"),
                            $imgName,
                            'blog/paragraphs'
                        );
                        $newUploads[] = ['name' => $newImage, 'folder' => 'blog/paragraphs'];
                        if ($paragraphImage) {
                            $toDeleteOnSuccess[] = ['name' => $paragraphImage, 'folder' => 'blog/paragraphs'];
                        }
                        $paragraphImage = $newImage;
                    } elseif (!empty($paragraph['remove_image']) && $paragraphImage) {
                        $toDeleteOnSuccess[] = ['name' => $paragraphImage, 'folder' => 'blog/paragraphs'];
                        $paragraphImage = null;
                    }

                    if (!empty($paragraph['id'])) {
                        $blog->paragraphs()->where('id', $paragraph['id'])->update([
                            'title' => $paragraph['title'] ?? null,
                            'content' => $paragraph['content'] ?? null,
                            'image' => $paragraphImage,
                            'sort_order' => $index,
                        ]);
                        $submittedIds[] = (int) $paragraph['id'];
                    } else {
                        $created = $blog->paragraphs()->create([
                            'title' => $paragraph['title'] ?? null,
                            'content' => $paragraph['content'] ?? null,
                            'image' => $paragraphImage,
                            'sort_order' => $index,
                        ]);
                        $submittedIds[] = $created->id;
                    }
                }
            }

            /* Delete paragraphs that were removed on the frontend */
            $removedParagraphs = $blog->paragraphs()->whereNotIn('id', $submittedIds)->get();
            foreach ($removedParagraphs as $removed) {
                if ($removed->image) {
                    $toDeleteOnSuccess[] = ['name' => $removed->image, 'folder' => 'blog/paragraphs'];
                }
            }
            $blog->paragraphs()->whereNotIn('id', $submittedIds)->delete();

            /* Gallery images: append new ones (existing ones removed via separate AJAX route) */
            if ($request->hasFile('images')) {
                $startOrder = $blog->images()->max('sort_order') + 1;
                foreach ($request->file('images') as $i => $imageFile) {
                    $imgName = ImageHelper::generateFileName(
                        $blog->title . '-gallery-' . ($startOrder + $i),
                        'blog-gallery'
                    );
                    $galleryImage = ImageHelper::uploadSingleImageWebpOnly(
                        $imageFile,
                        $imgName,
                        'blog/gallery'
                    );
                    $newUploads[] = ['name' => $galleryImage, 'folder' => 'blog/gallery'];

                    $blog->images()->create([
                        'image' => $galleryImage,
                        'alt_text' => $blog->title,
                        'sort_order' => $startOrder + $i,
                    ]);
                }
            }

            DB::commit();
            Cache::forget('api_blog_list');
            foreach ($toDeleteOnSuccess as $file) {
                ImageHelper::deleteSingleImage($file['name'], $file['folder']);
            }
            return redirect()->route('manage-blog.index')->with('success', 'Blog updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($newUploads as $file) {
                ImageHelper::deleteSingleImage($file['name'], $file['folder']);
            }
            Log::error('Blog update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->except(['main_image', 'page_image', 'images', 'paragraphs']))
                ->with('error', 'Something went wrong while updating the blog. Please try again.');
        }
    }


    public function destroy(Blog $blog)
    {
        DB::beginTransaction();
        try {
            if ($blog->main_image) {
                ImageHelper::deleteSingleImage($blog->main_image, 'blog');
            }
            if ($blog->page_image) {
                ImageHelper::deleteSingleImage($blog->page_image, 'blog');
            }
            foreach ($blog->paragraphs as $paragraph) {
                if ($paragraph->image) {
                    ImageHelper::deleteSingleImage($paragraph->image, 'blog/paragraphs');
                }
            }
            foreach ($blog->images as $image) {
                ImageHelper::deleteSingleImage($image->image, 'blog/gallery');
            }

            $blog->paragraphs()->delete();
            $blog->images()->delete();
            $blog->delete();

            DB::commit();
            return redirect()->route('manage-blog.index')->with('success', 'Blog deleted successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Blog deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete blog. Please try again.');
        }
    }

    public function destroyGalleryImage(BlogImage $image)
    {
        try {
            ImageHelper::deleteSingleImage($image->image, 'blog/gallery');
            $image->delete();
            return response()->json(['message' => 'Image deleted successfully']);
        } catch (\Throwable $e) {
            Log::error('Gallery image deletion failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete image'], 500);
        }
    }
}
