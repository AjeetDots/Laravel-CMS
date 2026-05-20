<?php

namespace App\Support;

class UserManualUrls
{
    public static function asset(string $path, bool $absolute = false): string
    {
        $url = asset($path);

        return $absolute ? url($url) : $url;
    }

    public static function route(string $name, array $parameters = [], bool $absolute = false): string
    {
        $url = route($name, $parameters);

        return $absolute ? url($url) : $url;
    }
}
