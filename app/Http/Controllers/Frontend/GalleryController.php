<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\GalleryItem;

class GalleryController extends Controller {
    public function index() {
        $gallery = GalleryItem::where('is_active', true)->orderBy('sort_order')->get();
        $categories = GalleryItem::where('is_active', true)->whereNotNull('category')->distinct()->pluck('category');
        return view('frontend.gallery', compact('gallery', 'categories'));
    }
}
