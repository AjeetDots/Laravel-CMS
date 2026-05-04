<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Portfolio;

class PortfolioController extends Controller {
    public function index() {
        $portfolios = Portfolio::where('is_active', true)->orderBy('sort_order')->orderBy('title')->get();
        $tags = $portfolios->flatMap(fn($p) => $p->tags ?? [])->unique()->sort()->values();
        return view('frontend.portfolio', compact('portfolios', 'tags'));
    }

    public function show(string $slug) {
        $portfolio = Portfolio::with('seoMeta')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $related = Portfolio::where('is_active', true)->where('id', '!=', $portfolio->id)
                              ->orderBy('sort_order')->limit(3)->get();
        $seoModel = $portfolio;
        return view('frontend.portfolio-detail', compact('portfolio', 'related', 'seoModel'));
    }
}
