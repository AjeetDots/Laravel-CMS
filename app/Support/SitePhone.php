<?php

namespace App\Support;

use App\Models\PhoneCountry;

/**
 * Normalises site phone settings for display, tel:, and WhatsApp links.
 */
final class SitePhone
{
    /**
     * Same visible format as Site settings: dial code + national number (no flag icons).
     */
    public static function formatFromCountry(PhoneCountry $country, string $national): string
    {
        $national = trim($national);

        return trim($country->dial_code.' '.$national);
    }

    /**
     * Public label for the site phone (dial code + number when structured; else legacy string).
     */
    public static function display(object $settings): string
    {
        $countryId = $settings->get('site_phone_country_id');
        $national = trim((string) ($settings->get('site_phone_national') ?? ''));
        if ($countryId !== null && $countryId !== '' && $national !== '') {
            $country = PhoneCountry::listingQuery()->whereKey((int) $countryId)->first();
            if ($country) {
                return self::formatFromCountry($country, $national);
            }
        }

        return trim((string) ($settings->get('site_phone') ?? ''));
    }

    public static function hasPhone(object $settings): bool
    {
        return self::telHref($settings) !== '';
    }

    /**
     * Value suitable for tel: URI (digits and leading + only).
     */
    public static function telHref(object $settings): string
    {
        $e164 = (string) ($settings->get('site_phone_e164') ?? '');
        $display = (string) ($settings->get('site_phone') ?? '');
        $raw = $e164 !== '' ? $e164 : $display;

        return $raw !== '' ? preg_replace('/[^\d+]/u', '', $raw) : '';
    }

    /**
     * Digits only (no +) for wa.me/{digits}.
     */
    public static function whatsappDigits(object $settings): string
    {
        $e164 = (string) ($settings->get('site_phone_e164') ?? '');
        $display = (string) ($settings->get('site_phone') ?? '');
        $raw = $e164 !== '' ? $e164 : $display;

        return $raw !== '' ? preg_replace('/\D/', '', $raw) : '';
    }
}
