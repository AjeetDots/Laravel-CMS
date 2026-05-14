<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterFooterContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY = 'footer_newsletter';

    /**
     * @return array<string, string>
     */
    public static function viewDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'heading' => 'Newsletter',
            'lead' => 'Occasional projects, tips, and offers. Unsubscribe anytime.',
            'email_label' => 'Email address',
            'placeholder' => 'Your email',
            'submit_label' => 'Subscribe',
            'submit_busy_label' => '…',
            'privacy_text' => 'We respect your privacy.',
            'message_success' => 'Thank you for subscribing!',
            'message_already_subscribed' => 'You have already subscribed with this email address.',
            'message_error_generic' => 'Please check your email and try again.',
            'message_error_network' => 'Something went wrong. Please try again.',
        ];

        $out = [];
        foreach ($defaults as $key => $default) {
            $val = $stored[$key] ?? null;
            $out[$key] = ($val !== null && $val !== '') ? (string) $val : $default;
        }

        return $out;
    }
}
