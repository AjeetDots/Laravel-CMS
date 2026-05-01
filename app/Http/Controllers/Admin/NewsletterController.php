<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller {
    public function index() {
        $subscribers = NewsletterSubscriber::orderByDesc('created_at')->paginate(20);
        return view('admin.newsletter.index', compact('subscribers'));
    }
    public function destroy(NewsletterSubscriber $subscriber) {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed.');
    }
}
