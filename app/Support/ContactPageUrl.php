<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Page;
use Illuminate\Support\Facades\Schema;

/**
 * Canonical public URL for the active CMS contact page.
 */
final class ContactPageUrl
{
    public static function activePage(): ?Page
    {
        if (! Schema::hasTable('pages')) {
            return null;
        }

        return Page::query()
            ->where('is_active', true)
            ->where('template', Page::TEMPLATE_CONTACT)
            ->orderByDesc('updated_at')
            ->first();
    }

    public static function canonicalSlug(): string
    {
        $page = static::activePage();
        $slug = strtolower(trim((string) ($page?->slug ?? ''), '/'));

        return $slug !== '' ? $slug : 'contact';
    }

    public static function path(): string
    {
        return '/'.static::canonicalSlug();
    }

    /**
     * Former / alternate contact paths that should 301 to the canonical URL.
     *
     * @return list<string>
     */
    public static function legacySlugs(): array
    {
        $canonical = static::canonicalSlug();
        $candidates = array_merge(Page::CONTACT_SLUGS, ['contact']);

        $legacy = [];
        foreach ($candidates as $slug) {
            $slug = strtolower(trim((string) $slug, '/'));
            if ($slug === '' || $slug === $canonical) {
                continue;
            }
            $legacy[] = $slug;
        }

        return array_values(array_unique($legacy));
    }
}
