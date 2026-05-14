<?php

namespace App\Support;

final class HomePageAdminTabs
{
    /** @var list<string> */
    public const SECTION_KEYS = [
        'atelier',
        'finishes',
        'services',
        'why',
        'process',
        'commissions',
        'begin-cta',
        'contact-band',
        'brands-strip',
        'blog-preview',
    ];

    public static function normalize(?string $value): string
    {
        return is_string($value) && $value !== '' && in_array($value, self::SECTION_KEYS, true)
            ? $value
            : 'atelier';
    }
}
