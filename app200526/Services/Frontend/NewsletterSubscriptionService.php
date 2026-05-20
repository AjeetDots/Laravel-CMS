<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Data\Frontend\NewsletterSubscriptionResult;
use App\Jobs\SendNewsletterSubscriptionEmailsJob;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriptionService implements NewsletterSubscriptionServiceInterface
{
    public function subscribe(string $email, ?string $name = null): NewsletterSubscriptionResult
    {
        $exists = NewsletterSubscriber::query()
            ->where('email', $email)
            ->exists();

        if ($exists) {
            return NewsletterSubscriptionResult::alreadySubscribed();
        }

        $subscriber = NewsletterSubscriber::query()->create([
            'email' => $email,
            'name' => $name,
        ]);

        SendNewsletterSubscriptionEmailsJob::dispatch($subscriber->id)->afterCommit();

        return NewsletterSubscriptionResult::subscribed();
    }
}
