<?php

namespace App\Data\Frontend;

final class NewsletterSubscriptionResult
{
    private function __construct(
        private readonly bool $alreadySubscribed
    ) {
    }

    public static function subscribed(): self
    {
        return new self(false);
    }

    public static function alreadySubscribed(): self
    {
        return new self(true);
    }

    public function isAlreadySubscribed(): bool
    {
        return $this->alreadySubscribed;
    }
}
