<?php

namespace App\Jobs;

use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Services\EmailTemplateSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewsletterSubscriptionEmailsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /**
     * @var array<int, int>
     */
    public array $backoff = [30, 120];

    public function __construct(
        public int $subscriberId
    ) {
    }

    public function handle(EmailTemplateSender $emailTemplateSender): void
    {
        $subscriber = NewsletterSubscriber::query()->find($this->subscriberId);
        if (! $subscriber) {
            return;
        }

        $email = (string) $subscriber->email;
        $displayName = $subscriber->name
            ? (string) $subscriber->name
            : (strstr($email, '@', true) ?: $email);

        $variables = [
            'email' => $email,
            'user_name' => $displayName,
            'name' => $displayName,
            'date' => now()->format('d M Y, H:i'),
        ];

        $emailTemplateSender->send(
            EmailTemplate::TYPE_NEWSLETTER_CLIENT,
            $email,
            $variables
        );

        $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');
        if ($adminEmail) {
            $emailTemplateSender->send(
                EmailTemplate::TYPE_NEWSLETTER_ADMIN,
                (string) $adminEmail,
                $variables
            );
        }
    }
}
