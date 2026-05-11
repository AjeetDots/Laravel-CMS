<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\NewsletterSubscribeRequest;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function __construct(
        private readonly NewsletterSubscriptionServiceInterface $newsletterSubscriptionService
    ) {
    }

    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $result = $this->newsletterSubscriptionService->subscribe(
            $request->email,
            $request->input('name')
        );

        $newsletterUrl = Str::before(url()->previous(), '#') . '#footer-newsletter';

        if ($result->isAlreadySubscribed()) {
            return redirect($newsletterUrl)
                ->withErrors(['email' => 'You have already subscribed with this email address.'])
                ->withInput();
        }

        return redirect($newsletterUrl)->with('newsletter_success', 'Thank you for subscribing!');
    }
}
