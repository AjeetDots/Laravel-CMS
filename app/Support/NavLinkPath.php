<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Active-state and path logic shared by header {@see \App\Models\Menu} and footer {@see \App\Models\FooterNavLink}.
 */
final class NavLinkPath
{
    public static function matchesCurrentPath(?string $href): bool
    {
        $href = trim((string) ($href ?? ''));
        if ($href === '' || $href === '#') {
            return false;
        }

        $current = trim((string) request()->path(), '/');
        $menuPath = self::normalizedPathFromHref($href);
        if ($menuPath === null) {
            return false;
        }

        if ($menuPath === '') {
            return $current === '' && request()->routeIs('home');
        }

        if ($menuPath === $current) {
            return true;
        }

        return str_starts_with($current, $menuPath.'/');
    }

    /**
     * Path segment(s) for this link, without leading/trailing slashes. '' = site root (home).
     */
    private static function normalizedPathFromHref(string $href): ?string
    {
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            $path = parse_url($href, PHP_URL_PATH);
            if ($path === null || $path === '' || $path === '/') {
                return '';
            }

            return trim($path, '/');
        }
        if ($href === '/') {
            return '';
        }

        return trim($href, '/');
    }
}
