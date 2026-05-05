<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;

class GalleryController extends Controller {
    public function index() {
        $gallery = GalleryItem::with('galleryCategory')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $categories = GalleryCategory::query()
            ->whereHas('galleryItems', fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        return view('frontend.gallery', compact('gallery', 'categories'));
    }
}
