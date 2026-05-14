<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryCategoryRequest;
use App\Http\Requests\Admin\UpdateGalleryCategoryRequest;
use App\Models\GalleryCategory;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    use AppliesAdminTableFilters;

    public function index()
    {
        $query = GalleryCategory::withCount('galleryItems');
        $this->applyAdminSearch($query, request('q'), ['name', 'slug']);
        $categories = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.gallery_categories.index', compact('categories'));
    }

    public function create()
    {
        $defaultSortOrder = AdminDefaultSortOrder::next(GalleryCategory::class);

        return view('admin.gallery_categories.form', [
            'category' => new GalleryCategory(),
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }

    public function store(StoreGalleryCategoryRequest $request)
    {
        $data = $request->validated();
        $baseSlug = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['slug'] = $this->ensureUniqueSlug((string) $baseSlug);
        GalleryCategory::create($data);

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(GalleryCategory $gallery_category)
    {
        return view('admin.gallery_categories.form', [
            'category' => $gallery_category,
            'defaultSortOrder' => null,
        ]);
    }

    public function update(UpdateGalleryCategoryRequest $request, GalleryCategory $gallery_category)
    {
        $data = $request->validated();
        $baseSlug = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['slug'] = $this->ensureUniqueSlug((string) $baseSlug, $gallery_category->id);
        $gallery_category->update($data);

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(GalleryCategory $gallery_category)
    {
        $items = $gallery_category->galleryItems()
            ->orderBy('title')
            ->get(['title']);

        if ($items->isNotEmpty()) {
            $titles = $items->pluck('title')->map(fn ($t) => trim((string) $t))->filter()->values();
            $list = $titles->take(12)->implode('", "');
            $suffix = $titles->count() > 12 ? ' (and '.($titles->count() - 12).' more)' : '';

            return redirect()->route('admin.gallery-categories.index')
                ->with('error', 'This category cannot be removed while it is assigned to gallery item(s): "'.$list.'"'.$suffix.'. Reassign those items to another category, or remove them, then try again.');
        }

        $gallery_category->delete();

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'The gallery category has been removed.');
    }

    private function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($slug);
        if ($slug === '') {
            $slug = 'category';
        }
        $original = $slug;
        $i = 2;
        while (
            GalleryCategory::withTrashed()->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
