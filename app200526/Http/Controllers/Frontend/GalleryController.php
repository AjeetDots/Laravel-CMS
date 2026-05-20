<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\GalleryPageContent;
use App\Support\CmsOutboundHref;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = GalleryItem::with('galleryCategory')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $categories = GalleryCategory::query()
            ->whereHas('galleryItems', fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $galleryPage = GalleryPageContent::listingDataWithDefaults();
        $galleryPage['empty_btn_href'] = CmsOutboundHref::resolve($galleryPage['empty_btn_url'] ?? null, 'contact');
        $galleryPage['bottom_btn_href'] = CmsOutboundHref::resolve($galleryPage['bottom_btn_url'] ?? null, 'contact');

        return view('frontend.gallery', compact('gallery', 'categories', 'galleryPage'));
    }
}
