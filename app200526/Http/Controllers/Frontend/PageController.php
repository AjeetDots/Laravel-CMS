<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactPageContent;
use App\Models\Page;
use App\Models\PhoneCountry;
use App\Services\Frontend\AboutPageService;
use App\Support\ContactPageUrl;

class PageController extends Controller
{
    public function __construct(
        private readonly AboutPageService $aboutPageService
    ) {}

    public function show(string $slug): mixed
    {
        $page = Page::with('seoMeta', 'sections')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if ($page->template === Page::TEMPLATE_CONTACT) {
            $canonical = ContactPageUrl::canonicalSlug();
            if (strtolower(trim($slug, '/')) !== $canonical) {
                return redirect()->route('contact', [], 301);
            }
        }

        $template = 'frontend.pages.'.$page->template;
        if (! view()->exists($template)) {
            $template = 'frontend.pages.'.Page::TEMPLATE_FULL_WIDTH;
        }

        $viewData = [
            'page' => $page,
            'seoModel' => $page,
        ];

        if ($page->template === 'about') {
            $viewData['aboutPage'] = $this->aboutPageService->viewData();
        }

        if ($page->template === Page::TEMPLATE_CONTACT) {
            $viewData['phoneCountries'] = PhoneCountry::listingQuery()->get(['id', 'iso_code', 'name', 'dial_code', 'flag_emoji']);
            $viewData['contactPage'] = ContactPageContent::viewDataWithDefaults();
            $viewData['contactHeroUrl'] = ContactPageContent::resolveHeroBackgroundUrl($viewData['contactPage']['hero_bg_image'] ?? null);
        }

        return view($template, $viewData);
    }
}
