<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;

class BlogController extends Controller {
    public function index() {
        $posts = BlogPost::where('is_active', true)
            ->orderByDesc('published_at')
            ->paginate(9);
        return view('frontend.blog.index', compact('posts'));
    }

    public function show(string $slug) {
        $post = BlogPost::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related = BlogPost::where('is_active', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->limit(3)->get();
        return view('frontend.blog.show', compact('post', 'related'));
    }
}
