<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller {

    public function index() {
        $posts = BlogPost::orderByDesc('created_at')->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create() {
        return view('admin.blog.create');
    }

    public function store(StoreBlogRequest $request) {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_active']    = $request->boolean('is_active', true);
        $data['published_at'] = $data['published_at'] ?? now();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        BlogPost::create($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog) {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(UpdateBlogRequest $request, BlogPost $blog) {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog) {
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return back()->with('success', 'Post deleted.');
    }
}
