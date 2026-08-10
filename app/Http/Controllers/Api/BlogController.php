<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
class BlogController extends Controller
{  

    public function blogList()
    {        
        $blogs = Blog::where('status', 'published')
            ->select(
                'id',
                'title',
                'slug',
                'short_desc',
                'content',
                'reading_time',
                'main_image',
                'page_image',
                'visitor_count',
                'published_at'
            )
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        $blogs->getCollection()->transform(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'visitor_count' => $blog->visitor_count ?? 0,
                'reading_time' => !empty($blog->reading_time)
                    ? $blog->reading_time
                    : null,
                'short_desc' => !empty($blog->short_desc)
                    ? $blog->short_desc
                    : (!empty($blog->content)
                        ? Str::limit(strip_tags($blog->content), 100)
                        : null),
                'main_image' => !empty($blog->main_image)
                    ? asset('storage/images/blog/' . $blog->main_image)
                    : null,

                'published_at' => !empty($blog->published_at)
                    ? Carbon::parse($blog->published_at)->format('d M Y')
                    : null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Blog list',
            'data' => $blogs->items(),
            'pagination' => [
                'current_page' => $blogs->currentPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
                'last_page' => $blogs->lastPage(),
                'from' => $blogs->firstItem(),
                'to' => $blogs->lastItem(),
                'next_page_url' => $blogs->nextPageUrl(),
                'prev_page_url' => $blogs->previousPageUrl(),
            ],
        ]);
    }

    public function blogDetails($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'paragraphs' => function ($q) {
                    $q->select(
                        'id',
                        'blog_id',
                        'title',
                        'content',
                        'image',
                        'sort_order'
                    )->orderBy('sort_order', 'asc');
                },
                'images' => function ($q) {
                    $q->select(
                        'id',
                        'blog_id',
                        'image',
                        'alt_text',
                        'sort_order'
                    )->orderBy('sort_order', 'asc');
                }
            ])
            ->first();
        if (!$blog) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
                'data' => []
            ], 404);
        }
        $blog->increment('visitor_count');
        $blog->refresh();
        Cache::forget('api_blog_list');
        $relatedBlogs = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->select(
                'id',
                'title',
                'slug',
                'short_desc',
                'content',
                'reading_time',
				'visitor_count',
                'main_image',
                'published_at'
            )
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'visitor_count' => $item->visitor_count ?? 0,
                    'reading_time' => $item->reading_time ?? null,
                    'short_desc' => $item->short_desc
                            ?? ($item->content
                                ? Str::limit(strip_tags($item->content), 100)
                                : null),                    
                    'main_image' => $item->main_image
                        ? asset('storage/images/blog/' . $item->main_image)
                        : null,
                    'published_at' => $item->published_at
                        ? Carbon::parse($item->published_at)->format('d M Y')
                        : null,
                ];
            });
        return response()->json([
            'status' => true,
            'message' => 'Blog details',
            'data' => [
                'id' => $blog->id,
				'meta_title' => ($blog->meta_title ?: $blog->title),
                'meta_description' => $blog->meta_description
                    ?: $blog->short_desc
                    ?: ($blog->content
                        ? Str::limit(strip_tags($blog->content), 160)
                        : null),
                'title' => $blog->title,
                'slug' => $blog->slug, 
                'visitor_count' => $blog->visitor_count ?? 0,
                'reading_time' => $blog->reading_time ?? null,               
                'short_desc' => $blog->short_desc
                    ?? null,
                'content' => $blog->content,
                'main_image' => $blog->main_image
                    ? asset('storage/images/blog/' . $blog->main_image)
                    : null,
                'page_image' => $blog->page_image
                    ? asset('storage/images/blog/' . $blog->page_image)
                    : null,
                'published_at' => $blog->published_at
                    ? Carbon::parse($blog->published_at)->format('d M Y')
                    : null,                
                'paragraphs' => $blog->paragraphs->map(function ($para) {
                    return [
                        'title' => $para->title,
                        'content' => $para->content,
                        'image' => $para->image
                            ? asset('storage/images/blog/paragraphs/' . $para->image)
                            : null,
                    ];
                })->values(),
                'images' => $blog->images->map(function ($img) {
                    return [
                        'image' => $img->image
                            ? asset('storage/images/blog/gallery/' . $img->image)
                            : null,
                        'alt_text' => $img->alt_text,
                    ];
                })->values(),
            ],
            'you_might_also_like' => $relatedBlogs,
        ]);
    }
}
