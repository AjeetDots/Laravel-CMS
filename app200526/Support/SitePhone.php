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
     * Public label for the WhatsApp number (dedicated setting, else site phone).
     */
    public static function whatsappDisplay(object $settings): string
    {
        $countryId = $settings->get('site_whatsapp_country_id');
        $national = trim((string) ($settings->get('site_whatsapp_national') ?? ''));
        if ($countryId !== null && $countryId !== '' && $national !== '') {
            $country = PhoneCountry::listingQuery()->whereKey((int) $countryId)->first();
            if ($country) {
                return self::formatFromCountry($country, $national);
            }
        }

        $display = trim((string) ($settings->get('site_whatsapp') ?? ''));
        if ($display !== '') {
            return $display;
        }

        return self::display($settings);
    }

    public static function hasWhatsapp(object $settings): bool
    {
        return self::whatsappDigits($settings) !== '';
    }

    /**
     * Digits only (no +) for wa.me/{digits}. Uses WhatsApp setting when set, else site phone.
     */
    public static function whatsappDigits(object $settings): string
    {
        $e164 = (string) ($settings->get('site_whatsapp_e164') ?? '');
        $display = (string) ($settings->get('site_whatsapp') ?? '');
        $raw = $e164 !== '' ? $e164 : $display;
        if ($raw === '') {
            $e164 = (string) ($settings->get('site_phone_e164') ?? '');
            $display = (string) ($settings->get('site_phone') ?? '');
            $raw = $e164 !== '' ? $e164 : $display;
        }

        return $raw !== '' ? preg_replace('/\D/', '', $raw) : '';
    }

    public static function whatsappHref(object $settings, ?string $message = null): string
    {
        $digits = self::whatsappDigits($settings);
        if ($digits === '') {
            return '';
        }

        $url = 'https://wa.me/'.$digits;
        $message = $message !== null ? trim($message) : '';
        if ($message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }
}
