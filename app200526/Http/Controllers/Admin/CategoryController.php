<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use AppliesAdminTableFilters;

    public function index()
    {
        $query = Category::with('parent')->withCount('posts');
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['name', 'slug']);
        $categories = $query
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::selectTree();
        $parentId = request()->filled('parent_id') ? (int) request('parent_id') : null;
        $defaultSortOrder = AdminDefaultSortOrder::next(Category::class, ['parent_id' => $parentId]);

        return view('admin.categories.form', [
            'parents' => $parents,
            'category' => new Category(),
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        $parents = Category::selectTree($category->id);

        return view('admin.categories.form', [
            'category' => $category,
            'parents' => $parents,
            'defaultSortOrder' => null,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        // Prevent a category from becoming its own parent
        if (!empty($data['parent_id']) && (int) $data['parent_id'] === $category->id) {
            $data['parent_id'] = null;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $postsCount = (int) $category->posts()->count();
        if ($postsCount > 0) {
            return back()->with(
                'error',
                'This category cannot be deleted because it is assigned to '
                . $postsCount
                . ' '
                . Str::plural('blog post', $postsCount)
                . '. Change the category on those posts (or remove them), then try again.'
            );
        }

        $category->delete();

        return back()->with('success', 'The category has been removed.');
    }
}
