<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Finish;
use App\Models\FinishesPageContent;
use App\Support\CmsOutboundHref;

class FinishController extends Controller
{
    public function index()
    {
        $finishes = Finish::where('is_active', true)->orderBy('sort_order')->orderBy('title')->get();
        $finishesPage = FinishesPageContent::listingDataWithDefaults();
        $finishesPage['empty_btn_href'] = CmsOutboundHref::resolve($finishesPage['empty_btn_url'] ?? null);
        $finishesPage['bottom_btn_href'] = CmsOutboundHref::resolve($finishesPage['bottom_btn_url'] ?? null);

        return view('frontend.finishes', compact('finishes', 'finishesPage'));
    }

    public function show(string $slug)
    {
        $finish = Finish::with(['seoMeta', 'services'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $related = Finish::where('is_active', true)->where('id', '!=', $finish->id)
            ->orderBy('sort_order')->limit(4)->get();
        $seoModel = $finish;

        return view('frontend.finish-detail', compact('finish', 'related', 'seoModel'));
    }
}
