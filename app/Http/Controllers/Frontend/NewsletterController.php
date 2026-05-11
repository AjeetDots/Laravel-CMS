<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\NewsletterSubscribeRequest;

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

        if ($request->expectsJson()) {
            if ($result->isAlreadySubscribed()) {
                $msg = 'You have already subscribed with this email address.';

                return response()->json([
                    'message' => $msg,
                    'errors' => ['email' => [$msg]],
                ], 422);
            }

            return response()->json([
                'message' => 'Thank you for subscribing!',
            ]);
        }

        if ($result->isAlreadySubscribed()) {
            return back()
                ->withErrors(['email' => 'You have already subscribed with this email address.'])
                ->withInput();
        }

        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }
}
