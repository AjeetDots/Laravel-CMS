<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Finish;

class FinishController extends Controller {
    public function index() {
        $finishes = Finish::where('is_active', true)->orderBy('sort_order')->orderBy('title')->get();
        return view('frontend.finishes', compact('finishes'));
    }

    public function show(string $slug) {
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
