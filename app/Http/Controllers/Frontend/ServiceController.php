<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServicesPageContent;
use App\Support\CmsOutboundHref;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $servicesPage = ServicesPageContent::listingDataWithDefaults();
        $servicesPage['empty_btn_href'] = CmsOutboundHref::resolve($servicesPage['empty_btn_url'] ?? null);
        $servicesPage['bottom_btn_href'] = CmsOutboundHref::resolve($servicesPage['bottom_btn_url'] ?? null);

        return view('frontend.services', compact('services', 'servicesPage'));
    }

    public function show(string $slug)
    {
        $service = Service::with(['seoMeta', 'finishes'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.service-detail', [
            'service' => $service,
            'seoModel' => $service,
        ]);
    }
}
