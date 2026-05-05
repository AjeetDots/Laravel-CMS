<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\HomePageServiceInterface;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Finish;
use App\Models\GalleryItem;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Testimonial;

class HomePageService implements HomePageServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getPageData(): array
    {
        return [
            'sliders' => Slider::query()
                ->where('is_active', true)
                ->where('panel', 'main')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'sliderRight1' => Slider::query()
                ->where('is_active', true)
                ->where('panel', 'right_top')
                ->first(),
            'sliderRight2' => Slider::query()
                ->where('is_active', true)
                ->where('panel', 'right_bottom')
                ->first(),
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get(),
            'finishes' => Finish::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit(6)
                ->get(),
            'portfolios' => Portfolio::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'gallery' => GalleryItem::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(8)
                ->get(),
            'testimonials' => Testimonial::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'blogPosts' => BlogPost::query()
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
        ];
    }
}
