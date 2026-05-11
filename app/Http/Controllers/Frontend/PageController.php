<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\Frontend\AboutPageService;

class PageController extends Controller
{
    public function __construct(
        private readonly AboutPageService $aboutPageService
    ) {}

    public function show(string $slug)
    {
        $page = Page::with('seoMeta', 'sections')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $template = 'frontend.pages.'.$page->template;
        if (! view()->exists($template)) {
            $template = 'frontend.pages.default';
        }

        $viewData = [
            'page' => $page,
            'seoModel' => $page,
        ];

        if ($page->template === 'about') {
            $viewData['aboutPage'] = $this->aboutPageService->viewData();
        }

        return view($template, $viewData);
    }
}
