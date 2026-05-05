<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Data\Frontend\NewsletterSubscriptionResult;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Services\EmailTemplateSender;

class NewsletterSubscriptionService implements NewsletterSubscriptionServiceInterface
{
    public function __construct(
        private readonly EmailTemplateSender $emailTemplateSender
    ) {
    }

    public function subscribe(string $email, ?string $name = null): NewsletterSubscriptionResult
    {
        $exists = NewsletterSubscriber::query()
            ->where('email', $email)
            ->exists();

        if ($exists) {
            return NewsletterSubscriptionResult::alreadySubscribed();
        }

        NewsletterSubscriber::query()->create([
            'email' => $email,
            'name' => $name,
        ]);

        $displayName = $name ?: (strstr($email, '@', true) ?: $email);

        try {
            $variables = [
                'email' => $email,
                'user_name' => $displayName,
                'name' => $displayName,
                'date' => now()->format('d M Y, H:i'),
            ];

            $this->emailTemplateSender->send(
                EmailTemplate::TYPE_NEWSLETTER_CLIENT,
                $email,
                $variables
            );

            $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');
            if ($adminEmail) {
                $this->emailTemplateSender->send(
                    EmailTemplate::TYPE_NEWSLETTER_ADMIN,
                    (string) $adminEmail,
                    $variables
                );
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        return NewsletterSubscriptionResult::subscribed();
    }
}
