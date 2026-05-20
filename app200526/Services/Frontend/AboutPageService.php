<?php

namespace App\Services\Frontend;

use App\Models\AboutPageContent;
use App\Support\CmsImage;
use App\Support\CmsOutboundHref;

class AboutPageService
{
    /**
     * CMS copy plus resolved image URLs and button href for the About template.
     *
     * @return array<string, mixed>
     */
    public function viewData(): array
    {
        $data = AboutPageContent::listingDataWithDefaults();

        $mainPath = $data['image_main'] ?? null;
        $accentPath = $data['image_accent'] ?? null;
        $studioPath = $data['image_studio'] ?? null;

        return array_merge($data, [
            'image_main_display' => $this->storageUrl($mainPath),
            'image_accent_display' => $this->storageUrl($accentPath),
            'image_studio_display' => $this->storageUrl($studioPath),
            'image_main_fallback' => CmsImage::defaultUrl(),
            'image_accent_fallback' => CmsImage::defaultUrl(),
            'image_studio_fallback' => CmsImage::defaultUrl(),
            'workshop_btn_href' => CmsOutboundHref::resolve($data['workshop_btn_url'] ?? null, 'contact'),
        ]);
    }

    private function storageUrl(mixed $storedRelativePath): string
    {
        $stored = $storedRelativePath !== null ? trim((string) $storedRelativePath) : '';

        return CmsImage::resolve($stored !== '' ? $stored : null);
    }
}
