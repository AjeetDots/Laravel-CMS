<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\GalleryItem;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller {
    public function index() {
        $items = GalleryItem::orderBy('sort_order')->get();
        return view('admin.gallery.index', compact('items'));
    }
    public function create() {
        return view('admin.gallery.form', ['item' => new GalleryItem()]);
    }
    public function store(StoreGalleryRequest $request) {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('gallery', 'public');
        $data['is_active'] = $request->boolean('is_active');
        GalleryItem::create($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Image uploaded.');
    }
    public function edit(GalleryItem $gallery) {
        return view('admin.gallery.form', ['item' => $gallery]);
    }
    public function update(UpdateGalleryRequest $request, GalleryItem $gallery) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($gallery->image) Storage::disk('public')->delete($gallery->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        $gallery->update($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Image updated.');
    }
    public function destroy(GalleryItem $gallery) {
        if ($gallery->image) Storage::disk('public')->delete($gallery->image);
        $gallery->delete();
        return back()->with('success', 'Image deleted.');
    }
}
