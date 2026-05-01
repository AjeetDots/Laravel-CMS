<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\GalleryItem;
use App\Models\Contact;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\Brand;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'services'     => Service::count(),
            'gallery'      => GalleryItem::count(),
            'contacts'     => Contact::count(),
            'unread'       => Contact::where('is_read', false)->count(),
            'sliders'      => Slider::count(),
            'testimonials' => Testimonial::count(),
            'brands'       => Brand::count(),
        ];
        $recentContacts = Contact::latest()->limit(5)->get();
        return view('admin.dashboard', compact('stats', 'recentContacts'));
    }
}
