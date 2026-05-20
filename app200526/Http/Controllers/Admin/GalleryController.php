<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = GalleryItem::with('galleryCategory');
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title']);
        if (request('gallery_category_id') === 'none') {
            $query->whereNull('gallery_category_id');
        } elseif (request()->filled('gallery_category_id')) {
            $query->where('gallery_category_id', (int) request('gallery_category_id'));
        }
        $items = $query->orderBy('sort_order')->get();
        $galleryCategoryOptions = GalleryCategory::orderBy('name')->pluck('name', 'id');

        return view('admin.gallery.index', compact('items', 'galleryCategoryOptions'));
    }
    public function create() {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        $defaultSortOrderByCategory = [];
        foreach ($categories as $cat) {
            $defaultSortOrderByCategory[(string) $cat->id] = AdminDefaultSortOrder::next(
                GalleryItem::class,
                ['gallery_category_id' => $cat->id]
            );
        }

        $pre = request('gallery_category_id');
        $defaultSortOrder = AdminDefaultSortOrder::next(GalleryItem::class);
        if ($pre !== null && $pre !== '' && $pre !== 'none') {
            $categoryKey = (string) (int) $pre;
            if (array_key_exists($categoryKey, $defaultSortOrderByCategory)) {
                $defaultSortOrder = $defaultSortOrderByCategory[$categoryKey];
            }
        }

        return view('admin.gallery.form', [
            'item' => new GalleryItem(),
            'categories' => $categories,
            'defaultSortOrder' => $defaultSortOrder,
            'defaultSortOrderByCategory' => $defaultSortOrderByCategory,
        ]);
    }
    public function store(StoreGalleryRequest $request) {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('gallery', 'public');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = max(1, (int) ($data['sort_order'] ?? 1));
        GalleryItem::create($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Image uploaded.');
    }
    public function edit(GalleryItem $gallery) {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.gallery.form', [
            'item' => $gallery,
            'categories' => $categories,
            'defaultSortOrder' => null,
        ]);
    }
    public function update(UpdateGalleryRequest $request, GalleryItem $gallery) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($gallery->image) Storage::disk('public')->delete($gallery->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        if (array_key_exists('sort_order', $data)) {
            $data['sort_order'] = max(1, (int) $data['sort_order']);
        }
        $gallery->update($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Image updated.');
    }
    public function destroy(GalleryItem $gallery) {
        $gallery->delete();
        return back()->with('success', 'The gallery item has been removed.');
    }
}
