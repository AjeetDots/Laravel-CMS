<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryCategoryRequest;
use App\Http\Requests\Admin\UpdateGalleryCategoryRequest;
use App\Models\GalleryCategory;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    public function index()
    {
        $categories = GalleryCategory::withCount('galleryItems')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.gallery_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.gallery_categories.form', ['category' => new GalleryCategory()]);
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
        return view('admin.gallery_categories.form', ['category' => $gallery_category]);
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
        $gallery_category->delete();

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'Category deleted. Gallery items in this category were unassigned.');
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
            GalleryCategory::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
