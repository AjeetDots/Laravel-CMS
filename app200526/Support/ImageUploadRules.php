<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * Required when nothing is stored yet, or when the user removes the stored file.
     * Otherwise optional (keeps existing file on edit).
     */
    public static function requiredUnlessStored(
        int $maxKb,
        ?string $storedPath,
        ?string $removeField = null
    ): array {
        $removed = $removeField !== null && request()->boolean($removeField);
        $hasStored = filled($storedPath) && ! $removed;

        return $hasStored ? self::nullable($maxKb) : self::required($maxKb);
    }

    /**
     * @param  Model|object|null  $model
     */
    public static function requiredUnlessModelColumn(
        int $maxKb,
        mixed $model,
        string $column,
        ?string $removeField = null
    ): array {
        $stored = null;
        if ($model instanceof Model && filled($model->{$column} ?? null)) {
            $stored = (string) $model->{$column};
        }

        return self::requiredUnlessStored($maxKb, $stored, $removeField);
    }

    public static function requiredUnlessSetting(
        int $maxKb,
        string $settingKey,
        ?string $removeField = null
    ): array {
        $stored = trim((string) (Setting::get($settingKey) ?? ''));

        return self::requiredUnlessStored($maxKb, $stored !== '' ? $stored : null, $removeField);
    }

    /** Favicon allows .ico in addition to standard image types. */
    public static function requiredFaviconUnlessSetting(?string $removeField = null): array
    {
        $stored = trim((string) (Setting::get('site_favicon') ?? ''));
        $removed = $removeField !== null && request()->boolean($removeField);
        $hasStored = $stored !== '' && ! $removed;

        return $hasStored
            ? ['nullable', File::types(['ico', 'png', 'svg', 'jpg', 'jpeg', 'gif', 'webp'])->max(512)]
            : ['required', File::types(['ico', 'png', 'svg', 'jpg', 'jpeg', 'gif', 'webp'])->max(512)];
    }

    public static function nullableFavicon(): array
    {
        return ['nullable', File::types(['ico', 'png', 'svg', 'jpg', 'jpeg', 'gif', 'webp'])->max(512)];
    }
}
