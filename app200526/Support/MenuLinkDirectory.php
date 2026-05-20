<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Page;

final class MenuLinkDirectory
{
    /**
     * Main site sections (paths match public routes in routes/web.php).
     *
     * @return list<array{path: string, label: string}>
     */
    public static function fixedSiteSections(): array
    {
        return [
            ['path' => '/', 'label' => 'Home'],
            ['path' => '/services', 'label' => 'Services'],
            ['path' => '/finishes', 'label' => 'Finishes'],
            ['path' => '/portfolio', 'label' => 'Portfolio'],
            ['path' => '/gallery', 'label' => 'Gallery'],
            ['path' => ContactPageUrl::path(), 'label' => 'Contact'],
            ['path' => '/blog', 'label' => 'Blog'],
        ];
    }

    /**
     * CMS pages (public URL is /{slug}).
     *
     * @return list<array{path: string, label: string}>
     */
    public static function cmsPages(): array
    {
        return Page::query()
            ->orderByDesc('is_active')
            ->orderBy('title')
            ->get(['title', 'slug', 'is_active'])
            ->filter(fn (Page $page) => trim((string) $page->slug) !== '')
            ->map(function (Page $page) {
                $slug = trim((string) $page->slug, '/');
                $path = '/' . $slug;
                $label = (string) $page->title;
                if (! $page->is_active) {
                    $label .= ' (draft)';
                }

                return ['path' => $path, 'label' => $label];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<string, list<array{path: string, label: string}>>
     */
    public static function choiceGroups(): array
    {
        return [
            'Site sections' => self::fixedSiteSections(),
            'Your pages' => self::cmsPages(),
        ];
    }

    /**
     * Canonical paths shown in the admin menu URL picker (for validation).
     *
     * @return list<string>
     */
    public static function allPresetPaths(): array
    {
        $paths = [];
        foreach (self::choiceGroups() as $links) {
            foreach ($links as $link) {
                $paths[] = self::normalizePath($link['path']);
            }
        }

        return array_values(array_unique($paths));
    }

    public static function normalizePath(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '' || $raw === '#') {
            return '';
        }
        if (preg_match('#^https?://#i', $raw)) {
            $path = parse_url($raw, PHP_URL_PATH);
            if ($path === null || $path === '' || $path === '/') {
                return '/';
            }

            return '/' . trim($path, '/');
        }
        if ($raw === '/') {
            return '/';
        }

        return '/' . trim($raw, '/');
    }

    public static function isPresetPath(string $raw): bool
    {
        $norm = self::normalizePath($raw);
        if ($norm === '') {
            return false;
        }

        return in_array($norm, self::allPresetPaths(), true);
    }

    public static function validationErrorForUrl(string $mode, ?string $url): ?string
    {
        $url = $url ?? '';

        if ($mode === 'preset') {
            $norm = self::normalizePath($url);
            if ($norm === '') {
                return 'Choose a page or section for the link, or pick "Custom URL".';
            }
            if (! self::isPresetPath($url)) {
                return 'The URL must match a page from the list.';
            }

            return null;
        }

        return null;
    }
}
