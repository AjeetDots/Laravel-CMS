<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller {
    public function index() {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        return view('frontend.services', compact('services'));
    }

    public function show(string $slug) {
        $service = Service::with(['seoMeta', 'finishes'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.service-detail', [
            'service'  => $service,
            'seoModel' => $service,
        ]);
    }
}
