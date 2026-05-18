<?php

namespace App\Support;

/**
 * National phone numbers: digits only (country dial code is chosen separately).
 */
final class PhoneDigits
{
    public const NATIONAL_MAX = 24;

    public const COMBINED_MAX = 48;

    public const NATIONAL_REGEX = '/^\d*$/';

    /** Combined hidden value from intl widget: "+44 7123456789" */
    public const COMBINED_REGEX = '/^\+\d{1,4}\s\d{1,24}$/';

    public static function sanitizeNational(?string $value): string
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        return substr($digits, 0, self::NATIONAL_MAX);
    }

    /**
     * Normalise combined phone from the public contact form (dial code + national digits).
     */
    public static function normalizeCombined(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        $value = preg_replace('/[^\d+\s]/', '', $value);
        $value = preg_replace('/\s+/', ' ', trim($value));

        if (! preg_match('/^(\+\d{1,4})\s*(\d*)$/', $value, $matches)) {
            return '';
        }

        $dial = $matches[1];
        $national = self::sanitizeNational($matches[2] ?? '');

        return $national === '' ? '' : $dial.' '.$national;
    }

    public static function isValidNational(?string $value): bool
    {
        $value = (string) $value;

        return $value === '' || (bool) preg_match(self::NATIONAL_REGEX, $value);
    }

    public static function isValidCombined(?string $value): bool
    {
        $value = trim((string) $value);

        return $value === '' || (bool) preg_match(self::COMBINED_REGEX, $value);
    }

    /**
     * @return array<int, string|\Illuminate\Validation\Rules\Unique>
     */
    public static function nationalRules(): array
    {
        return ['nullable', 'string', 'max:'.self::NATIONAL_MAX, 'regex:'.self::NATIONAL_REGEX];
    }

    /**
     * @return array<int, string>
     */
    public static function combinedRules(): array
    {
        return ['nullable', 'string', 'max:'.self::COMBINED_MAX, 'regex:'.self::COMBINED_REGEX];
    }
}
