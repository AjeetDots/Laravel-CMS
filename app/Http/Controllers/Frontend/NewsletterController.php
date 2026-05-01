<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller {
    public function subscribe(NewsletterSubscribeRequest $request) {
        $exists = NewsletterSubscriber::where('email', $request->email)->exists();
        if (!$exists) {
            NewsletterSubscriber::create([
                'email' => $request->email,
                'name'  => $request->name ?? null,
            ]);
        }

        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }
}
