<?php

namespace App\Services\Frontend;

use App\Models\AboutPageContent;
use App\Models\GalleryItem;
use App\Support\CmsOutboundHref;

class AboutPageService
{
    private const FALLBACK_MAIN = 'https://placehold.co/900x1200/e5e0d8/6b6b65?text=Story+Image';

    private const FALLBACK_ACCENT = 'https://placehold.co/640x480/e5e0d8/6b6b65?text=Accent+Image';

    private const FALLBACK_STUDIO = 'https://placehold.co/1200x760/e5e0d8/6b6b65?text=Workshop+Image';

    /**
     * CMS copy plus resolved image URLs and button href for the About template.
     *
     * @return array<string, mixed>
     */
    public function viewData(): array
    {
        $data = AboutPageContent::listingDataWithDefaults();

        $gallery = GalleryItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        $mainFromGallery = $gallery->get(0)?->image_url;
        $accentFromGallery = $gallery->get(1)?->image_url;
        $studioFromGallery = $gallery->get(2)?->image_url;

        $mainPath = $data['image_main'] ?? null;
        $accentPath = $data['image_accent'] ?? null;
        $studioPath = $data['image_studio'] ?? null;

        $mainUrl = $this->urlFromStoredOrGallery($mainPath, $mainFromGallery, self::FALLBACK_MAIN);
        $accentUrl = $this->urlFromStoredOrGallery($accentPath, $accentFromGallery, self::FALLBACK_ACCENT);
        $studioUrl = $this->urlFromStoredOrGallery($studioPath, $studioFromGallery, null);
        if ($studioUrl === null) {
            $studioUrl = $mainUrl !== '' ? $mainUrl : self::FALLBACK_MAIN;
        }

        return array_merge($data, [
            'image_main_display' => $mainUrl,
            'image_accent_display' => $accentUrl,
            'image_studio_display' => $studioUrl,
            'image_main_fallback' => self::FALLBACK_MAIN,
            'image_accent_fallback' => self::FALLBACK_ACCENT,
            'image_studio_fallback' => self::FALLBACK_STUDIO,
            'workshop_btn_href' => CmsOutboundHref::resolve($data['workshop_btn_url'] ?? null),
        ]);
    }

    private function urlFromStoredOrGallery(?string $storedRelativePath, ?string $galleryUrl, ?string $placeholderIfEmpty): string
    {
        $stored = $storedRelativePath !== null ? trim($storedRelativePath) : '';
        if ($stored !== '') {
            return asset('storage/'.$stored);
        }
        if ($galleryUrl !== null && $galleryUrl !== '') {
            return $galleryUrl;
        }
        if ($placeholderIfEmpty !== null) {
            return $placeholderIfEmpty;
        }

        return '';
    }
}
