<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Finish;
use App\Models\GalleryItem;
use App\Models\Contact;
use App\Models\Portfolio;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'enquiries'        => Contact::count(),
            'unread_enquiries' => Contact::where('is_read', false)->count(),
            'services'         => Service::count(),
            'finishes'         => Finish::count(),
            'portfolio'        => Portfolio::count(),
            'gallery'          => GalleryItem::count(),
        ];
        $recentEnquiries = Contact::latest()->limit(5)->get();
        return view('admin.dashboard', compact('stats', 'recentEnquiries'));
    }
}
