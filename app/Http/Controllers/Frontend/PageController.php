<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller {
    public function show(string $slug) {
        $page = Page::with('seoMeta','sections')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $template = 'frontend.pages.' . $page->template;
        if (!view()->exists($template)) {
            $template = 'frontend.pages.default';
        }

        return view($template, [
            'page'     => $page,
            'seoModel' => $page,
        ]);
    }
}
