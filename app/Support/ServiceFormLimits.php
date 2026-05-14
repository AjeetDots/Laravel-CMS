<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Field size limits for the admin Services form (must stay in sync with validation rules).
 */
final class ServiceFormLimits
{
    public const TITLE_MAX = 200;

    public const SLUG_MAX = 200;

    public const SHORT_DESCRIPTION_MAX = 500;

    /** Rich-text / HTML — caps oversized submissions while staying under LONGTEXT. */
    public const DESCRIPTION_MAX = 100000;

    public const ICON_MAX = 100;

    public const BADGE_MAX = 100;

    public const FEATURE_LINE_MAX = 255;
}
