<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Resolves CMS upload paths to public URLs, with a single project-wide default image.
 */
final class CmsImage
{
    public static function defaultPath(): string
    {
        return (string) config('cms.default_image', 'images/header-bg.jpg');
    }

    public static function defaultUrl(): string
    {
        $path = ltrim(static::defaultPath(), '/');

        return asset($path);
    }

    /**
     * @param  array<int, mixed>  $paths
     * @return list<string>
     */
    public static function resolveMany(array $paths): array
    {
        return array_values(array_map(
            fn ($path) => static::resolve(is_string($path) ? $path : null),
            $paths
        ));
    }

    /**
     * Turn a stored relative path, absolute URL, or empty value into a display URL.
     * Missing or unreadable uploads fall back to the configured default image.
     */
    public static function resolve(?string $path): string
    {
        $path = $path !== null ? trim($path) : '';
        if ($path === '') {
            return static::defaultUrl();
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $relative = ltrim($path, '/');
        if (Storage::disk('public')->exists($relative)) {
            return asset('storage/'.$relative);
        }

        return static::defaultUrl();
    }

    public static function isDefaultUrl(string $url): bool
    {
        return rtrim($url, '/') === rtrim(static::defaultUrl(), '/');
    }
}
