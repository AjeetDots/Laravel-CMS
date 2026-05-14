<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\BlogPost;
use App\Models\Category;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = BlogPost::with('category')->orderByDesc('created_at');
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title', 'slug', 'author']);
        if (request()->filled('category_id')) {
            $query->where('category_id', (int) request('category_id'));
        }
        $posts = $query->get();
        $categoryOptions = Category::orderBy('name')->pluck('name', 'id');
        $defaultAuthorName = Auth::user()?->name ?: 'Editor';

        return view('admin.blog.index', compact('posts', 'categoryOptions', 'defaultAuthorName'));
    }

    public function create() {
        $categories = Category::selectTree();
        $categoryId = request()->filled('category_id') ? (int) request('category_id') : null;
        $defaultSortOrder = AdminDefaultSortOrder::next(BlogPost::class, ['category_id' => $categoryId]);

        return view('admin.blog.create', compact('categories', 'defaultSortOrder'));
    }

    public function store(StoreBlogRequest $request) {
        $data = $request->validated();

        $data['is_active']    = $request->boolean('is_active', true);
        $data['published_at'] = $data['published_at'] ?? now();

        if (! filled(trim((string) ($data['author'] ?? '')))) {
            $data['author'] = $request->user()?->name ?: 'Editor';
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $post = BlogPost::create($data);
        $post->saveSeo($request->input('seo', []));

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog) {
        $blog->load('seoMeta');
        $categories = Category::selectTree();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(UpdateBlogRequest $request, BlogPost $blog) {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active', true);

        if (! filled(trim((string) ($data['author'] ?? '')))) {
            $data['author'] = $request->user()?->name ?: 'Editor';
        }

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $blog->update($data);
        $blog->saveSeo($request->input('seo', []));

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog) {
        $blog->delete();
        return back()->with('success', 'The blog post has been removed.');
    }
}
