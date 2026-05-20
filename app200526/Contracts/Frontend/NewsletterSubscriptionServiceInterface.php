<?php

namespace App\Contracts\Frontend;

use App\Data\Frontend\NewsletterSubscriptionResult;

interface NewsletterSubscriptionServiceInterface
{
    public function subscribe(string $email, ?string $name = null): NewsletterSubscriptionResult;
}
