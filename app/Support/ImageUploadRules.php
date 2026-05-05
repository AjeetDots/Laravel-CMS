<?php

namespace App\Support;

use Illuminate\Validation\Rules\File;

/**
 * Shared image upload validation (SVG often fails Laravel's generic "image" rule).
 *
 * @see https://laravel.com/docs/validation#rule-file
 */
final class ImageUploadRules
{
    /** @var list<string> */
    public const EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    /** Nullable single image upload. */
    public static function nullable(int $maxKb): array
    {
        return ['nullable', File::types(self::EXTENSIONS)->max($maxKb)];
    }

    /** Required single image upload. */
    public static function required(int $maxKb): array
    {
        return ['required', File::types(self::EXTENSIONS)->max($maxKb)];
    }

}
