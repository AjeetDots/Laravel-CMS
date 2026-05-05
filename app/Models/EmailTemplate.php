<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailTemplate extends Model
{
    public const TYPE_CONTACT_CLIENT = 'contact_client';

    public const TYPE_CONTACT_ADMIN = 'contact_admin';

    public const TYPE_NEWSLETTER_CLIENT = 'newsletter_client';

    public const TYPE_NEWSLETTER_ADMIN = 'newsletter_admin';

    /** @var array<string, string> */
    public static array $templateTypeLabels = [
        self::TYPE_CONTACT_CLIENT => 'Contact enquiry - Client auto-reply',
        self::TYPE_CONTACT_ADMIN => 'Contact enquiry - Admin notification',
        self::TYPE_NEWSLETTER_CLIENT => 'Newsletter subscription - Client welcome',
        self::TYPE_NEWSLETTER_ADMIN => 'Newsletter subscription - Admin notification',
    ];

    /** @var array<string, string> */
    public static array $templateAudienceLabels = [
        'client' => 'Client Communication',
        'admin' => 'Admin Notifications',
    ];

    protected $fillable = ['name', 'slug', 'template_type', 'subject', 'body', 'placeholders', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'placeholders' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($t) {
            if (empty($t->slug)) {
                $t->slug = Str::slug($t->name);
            }
        });
    }

    /**
     * Read-only reference for admin UI (labels + example tokens).
     *
     * @return array<string, string>
     */
    public static function shortcodeReference(): array
    {
        return [
            '{{user_name}}' => 'Submitter’s name (alias: {{name}})',
            '{{name}}' => 'Same as user_name',
            '{{email}}' => 'Email address',
            '{{phone}}' => 'Phone (alias: {{contact_no}})',
            '{{contact_no}}' => 'Same as phone',
            '{{subject}}' => 'Message subject line',
            '{{message}}' => 'Message body (alias: {{msg}})',
            '{{msg}}' => 'Same as message',
            '{{date}}' => 'Date/time of submission',
        ];
    }

    /**
     * Fixed, system-managed templates (only these two are editable in admin).
     *
     * @return array<string, array<string, string>>
     */
    public static function fixedTemplateDefaults(): array
    {
        return [
            self::TYPE_CONTACT_CLIENT => [
                'name' => 'Contact Form Auto Reply',
                'slug' => 'contact-form-auto-reply',
                'subject' => 'Thank you {{user_name}}, we received your enquiry',
                'body' => "<p>Hi {{user_name}},</p>\n<p>Thank you for contacting us. We received your enquiry and our team will get back to you soon.</p>\n<p><strong>Your submitted details:</strong></p>\n<ul>\n<li>Email: {{email}}</li>\n<li>Contact No: {{contact_no}}</li>\n<li>Subject: {{subject}}</li>\n<li>Message: {{msg}}</li>\n<li>Date: {{date}}</li>\n</ul>\n<p>Regards,<br>{{site_name}}</p>",
            ],
            self::TYPE_CONTACT_ADMIN => [
                'name' => 'Contact Form Admin Alert',
                'slug' => 'contact-form-admin-alert',
                'subject' => 'New contact enquiry from {{user_name}}',
                'body' => "<p>A new contact enquiry has been submitted.</p>\n<ul>\n<li>Name: {{user_name}}</li>\n<li>Email: {{email}}</li>\n<li>Contact No: {{contact_no}}</li>\n<li>Subject: {{subject}}</li>\n<li>Message: {{msg}}</li>\n<li>Date: {{date}}</li>\n</ul>",
            ],
            self::TYPE_NEWSLETTER_CLIENT => [
                'name' => 'Newsletter Welcome',
                'slug' => 'newsletter-welcome',
                'subject' => 'Welcome {{user_name}} - Newsletter subscription confirmed',
                'body' => "<p>Hello {{user_name}},</p>\n<p>Thanks for subscribing to our newsletter with {{email}}.</p>\n<p>You will now receive our latest updates and offers.</p>\n<p>Subscribed on: {{date}}</p>\n<p>Regards,<br>{{site_name}}</p>",
            ],
            self::TYPE_NEWSLETTER_ADMIN => [
                'name' => 'Newsletter Admin Alert',
                'slug' => 'newsletter-admin-alert',
                'subject' => 'New newsletter subscription: {{email}}',
                'body' => "<p>A new newsletter subscription was received.</p>\n<ul>\n<li>Name: {{user_name}}</li>\n<li>Email: {{email}}</li>\n<li>Date: {{date}}</li>\n</ul>",
            ],
        ];
    }

    public static function audienceForType(?string $type): string
    {
        if (! $type) {
            return 'client';
        }

        return str_contains($type, 'admin') ? 'admin' : 'client';
    }

    public function renderSubject(array $vars): string
    {
        return $this->replaceVars($this->subject, $vars, false);
    }

    public function renderBody(array $vars): string
    {
        return $this->replaceVars($this->body, $vars, true);
    }

    /**
     * Replace {{token}} and aliases. HTML-escape values when $htmlContext (email body).
     */
    protected function replaceVars(string $text, array $vars, bool $htmlContext): string
    {
        $aliases = [
            'name' => ['user_name', 'name'],
            'user_name' => ['user_name', 'name'],
            'msg' => ['msg', 'message'],
            'message' => ['msg', 'message'],
            'contact_no' => ['contact_no', 'phone'],
            'phone' => ['contact_no', 'phone'],
        ];

        $flat = $vars;
        foreach ($aliases as $canonical => $keys) {
            $val = null;
            foreach ($keys as $k) {
                if (array_key_exists($k, $vars) && $vars[$k] !== null && $vars[$k] !== '') {
                    $val = $vars[$k];
                    break;
                }
            }
            if ($val !== null) {
                foreach ($keys as $k) {
                    $flat[$k] = $val;
                }
            }
        }

        return preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', function ($m) use ($flat, $htmlContext) {
            $key = $m[1];
            $val = $flat[$key] ?? '';

            return $htmlContext ? e((string) $val) : $this->escapeSubject((string) $val);
        }, $text);
    }

    protected function escapeSubject(string $val): string
    {
        return str_replace(["\r", "\n"], ' ', strip_tags($val));
    }

    /** @deprecated use renderBody */
    public function render(array $vars): string
    {
        return $this->renderBody($vars);
    }
}
