<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Cache keys for data injected into the public site layout (see ViewServiceProvider).
 */
final class FrontendViewCache
{
    public const SETTINGS_PLUCK_KEY = 'cms:frontend:settings_pluck';

    public const NAV_MENUS_KEY = 'cms:frontend:nav_menus';

    public static function forgetSettingsPluck(): void
    {
        Cache::forget(self::SETTINGS_PLUCK_KEY);
    }

    public static function forgetNavMenus(): void
    {
        Cache::forget(self::NAV_MENUS_KEY);
    }

    public static function forgetAll(): void
    {
        self::forgetSettingsPluck();
        self::forgetNavMenus();
    }
}
