<?php

namespace App\Support;

use App\Models\Setting;

final class CmsModuleVisibility
{
    public static function settingKey(string $module): string
    {
        return 'module_'.$module.'_enabled';
    }

    /**
     * Whether a CMS module is enabled on the public site (default: enabled).
     */
    public static function isEnabled(string $module): bool
    {
        if (! CmsModuleRegistry::has($module)) {
            return true;
        }

        $stored = Setting::get(self::settingKey($module));

        if ($stored === null || $stored === '') {
            return true;
        }

        return in_array(strtolower((string) $stored), ['1', 'true', 'yes', 'on'], true);
    }

    public static function setEnabled(string $module, bool $enabled): void
    {
        if (! CmsModuleRegistry::has($module)) {
            return;
        }

        Setting::set(self::settingKey($module), $enabled ? '1' : '0');
    }
}
