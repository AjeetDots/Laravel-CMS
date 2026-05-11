<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')
            ->where('is_active', true)
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('frontend.blog.index', compact('posts'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = BlogPost::with('category')
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('frontend.blog.index', compact('posts', 'category'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::with(['seoMeta', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 5 latest posts excluding this one
        $latestPosts = BlogPost::query()
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        // Up to 3 posts in the same category for the "Related" section
        $related = collect();
        if ($post->category_id) {
            $related = BlogPost::query()
                ->with('category')
                ->where('is_active', true)
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
        }

        return view('frontend.blog.show', [
            'post'        => $post,
            'latestPosts' => $latestPosts,
            'related'     => $related,
            'seoModel'    => $post,
        ]);
    }
}
