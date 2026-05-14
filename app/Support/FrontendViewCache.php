<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Cache keys for data injected into the public site layout (see ViewServiceProvider).
 */
final class FrontendViewCache
{
    /** Plain array in cache (not Collection) — safe for database/redis serializers. */
    public const SETTINGS_PLUCK_KEY = 'cms:frontend:settings_pluck_v2';

    /** Footer newsletter copy (see NewsletterFooterContent). */
    public const NEWSLETTER_FOOTER_KEY = 'cms:frontend:newsletter_footer_v1';

    /** @deprecated Legacy key when menus were cached as Eloquent; cleared on menu save. */
    public const NAV_MENUS_LEGACY_KEY = 'cms:frontend:nav_menus';

    public static function forgetSettingsPluck(): void
    {
        Cache::forget(self::SETTINGS_PLUCK_KEY);
        Cache::forget('cms:frontend:settings_pluck');
    }

    public static function forgetNavMenus(): void
    {
        Cache::forget(self::NAV_MENUS_LEGACY_KEY);
    }

    public static function forgetAll(): void
    {
        self::forgetSettingsPluck();
        self::forgetNavMenus();
        self::forgetNewsletterFooter();
    }

    public static function forgetNewsletterFooter(): void
    {
        Cache::forget(self::NEWSLETTER_FOOTER_KEY);
    }
}
