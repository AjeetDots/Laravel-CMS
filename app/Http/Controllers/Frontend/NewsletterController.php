<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\NewsletterSubscribeRequest;
use App\Models\NewsletterFooterContent;

class NewsletterController extends Controller
{
    public function __construct(
        private readonly NewsletterSubscriptionServiceInterface $newsletterSubscriptionService
    ) {
    }

    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $copy = NewsletterFooterContent::viewDataWithDefaults();

        $result = $this->newsletterSubscriptionService->subscribe(
            $request->email,
            $request->input('name')
        );

        if ($request->expectsJson()) {
            if ($result->isAlreadySubscribed()) {
                $msg = $copy['message_already_subscribed'];

                return response()->json([
                    'message' => $msg,
                    'errors' => ['email' => [$msg]],
                ], 422);
            }

            return response()->json([
                'message' => $copy['message_success'],
            ]);
        }

        if ($result->isAlreadySubscribed()) {
            return back()
                ->withErrors(['email' => $copy['message_already_subscribed']])
                ->withInput();
        }

        return back()->with('newsletter_success', $copy['message_success']);
    }
}
