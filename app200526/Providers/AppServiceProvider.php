<?php

namespace App\Providers;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Contracts\Frontend\HomePageServiceInterface;
use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Models\Setting;
use App\Services\Frontend\ContactSubmissionService;
use App\Services\Frontend\HomePageService;
use App\Services\Frontend\NewsletterSubscriptionService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HomePageServiceInterface::class, HomePageService::class);
        $this->app->bind(ContactSubmissionServiceInterface::class, ContactSubmissionService::class);
        $this->app->bind(NewsletterSubscriptionServiceInterface::class, NewsletterSubscriptionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $this->applyOutboundMailFromDatabaseSettings();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token): string {
            return route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], true);
        });
    }

    /**
     * When Site settings include an SMTP host, merge those values into the
     * default mailer so queued and synchronous mail uses the admin panel config.
     */
    protected function applyOutboundMailFromDatabaseSettings(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        try {
            $host = trim((string) Setting::get('mail_smtp_host', ''));
            if ($host === '') {
                return;
            }

            $portRaw = Setting::get('mail_smtp_port');
            $port = ($portRaw !== null && $portRaw !== '') ? (int) $portRaw : 587;
            $port = max(1, min(65535, $port));

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', Setting::get('mail_smtp_username'));

            $enc = strtolower(trim((string) (Setting::get('mail_smtp_encryption') ?? '')));
            Config::set('mail.mailers.smtp.encryption', in_array($enc, ['tls', 'ssl'], true) ? $enc : null);

            $pwdStored = Setting::get('mail_smtp_password');
            if (! empty($pwdStored) && is_string($pwdStored)) {
                try {
                    Config::set('mail.mailers.smtp.password', Crypt::decryptString($pwdStored));
                } catch (DecryptException) {
                    Config::set('mail.mailers.smtp.password', $pwdStored);
                }
            }

            $fromAddr = trim((string) (Setting::get('mail_from_address') ?? ''));
            if ($fromAddr !== '') {
                Config::set('mail.from.address', $fromAddr);
            }
            $fromName = trim((string) (Setting::get('mail_from_name') ?? ''));
            if ($fromName !== '') {
                Config::set('mail.from.name', $fromName);
            }
        } catch (\Throwable) {
            // Avoid boot failure during install or partial migrations.
        }
    }
}
