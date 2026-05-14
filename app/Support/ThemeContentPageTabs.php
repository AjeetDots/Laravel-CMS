<?php

namespace App\Support;

final class ThemeContentPageTabs
{
    public const SESSION_FLASH_KEY = 'theme_options_active_section';

    /** Finishes / Services / Gallery / Portfolio listing editors */
    public const LISTING_INTRO_GRID_BOTTOM = ['intro', 'grid', 'bottom'];

    public const ABOUT = ['intro', 'story', 'stats', 'workshop'];

    public const CONTACT = ['hero', 'info', 'form', 'map'];

    /**
     * @param  list<string>  $allowedKeys
     */
    public static function resolve(array $allowedKeys, string $fallback): string
    {
        $q = request()->query('section');
        if (is_string($q) && $q !== '' && in_array($q, $allowedKeys, true)) {
            return $q;
        }
        $f = session(self::SESSION_FLASH_KEY);
        if (is_string($f) && $f !== '' && in_array($f, $allowedKeys, true)) {
            return $f;
        }

        return in_array($fallback, $allowedKeys, true) ? $fallback : ($allowedKeys[0] ?? 'intro');
    }

    /**
     * @param  list<string>  $allowedKeys
     */
    public static function normalizeIn(array $allowedKeys, string $fallback, mixed $value): string
    {
        $v = is_string($value) ? trim($value) : '';

        return $v !== '' && in_array($v, $allowedKeys, true) ? $v : $fallback;
    }
}
