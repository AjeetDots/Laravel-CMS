<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Service;
use App\Models\Finish;
use App\Models\Portfolio;
use App\Models\GalleryItem;
use App\Models\Testimonial;
use App\Models\Brand;
use App\Models\BlogPost;

class HomeController extends Controller {
    public function index() {
        $sliders      = Slider::where('is_active', true)->where('panel', 'main')->orderBy('sort_order')->orderBy('id')->get();
        $sliderRight1 = Slider::where('is_active', true)->where('panel', 'right_top')->first();
        $sliderRight2 = Slider::where('is_active', true)->where('panel', 'right_bottom')->first();
        $services     = Service::where('is_active', true)->orderBy('sort_order')->limit(6)->get();
        $finishes     = Finish::where('is_active', true)->orderBy('sort_order')->limit(6)->get();
        $portfolios   = Portfolio::where('is_active', true)->orderBy('sort_order')->limit(4)->get();
        $gallery      = GalleryItem::where('is_active', true)->orderBy('sort_order')->limit(8)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();
        $brands       = Brand::where('is_active', true)->orderBy('sort_order')->get();
        $blogPosts    = BlogPost::where('is_active', true)->orderByDesc('published_at')->limit(3)->get();
        return view('frontend.home', compact(
            'sliders', 'sliderRight1', 'sliderRight2',
            'services', 'finishes', 'portfolios',
            'gallery', 'testimonials', 'brands', 'blogPosts'
        ));
    }
}
