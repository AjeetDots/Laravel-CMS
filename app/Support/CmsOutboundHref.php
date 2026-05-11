<?php

namespace App\Support;

class CmsOutboundHref
{
    public static function resolve(?string $url, string $fallbackRouteName = 'contact'): string
    {
        $url = $url !== null ? trim($url) : '';
        if ($url === '') {
            return route($fallbackRouteName);
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        if (str_starts_with($url, '/')) {
            return url($url);
        }

        return url($url);
    }
}
