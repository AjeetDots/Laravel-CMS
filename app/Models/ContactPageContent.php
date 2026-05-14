<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY = 'contact_page';

    /** Default value for the hidden `subject` field on the public contact form (email routing / inbox context). */
    public const DEFAULT_ENQUIRY_SUBJECT = 'Website enquiry (contact)';

    /**
     * @return array<string, mixed>
     */
    public static function viewDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'hero_line_1' => '',
            'hero_line_2' => '',
            'hero_cta' => '',
            'hero_bg_image' => '',
            'info_eyebrow' => '',
            'info_heading_1' => '',
            'info_heading_2' => '',
            'info_lead' => '',
            'studio_label' => '',
            'studio_body' => '',
            'hours_label' => '',
            'hours_body' => '',
            'appointment_line' => '',
            'fallback_phone_display' => '',
            'fallback_whatsapp_label' => '',
            'form_title' => '',
            'form_error_intro' => '',
            'subject_default' => self::DEFAULT_ENQUIRY_SUBJECT,
            'name_placeholder' => '',
            'email_placeholder' => '',
            'phone_field_label' => '',
            'national_placeholder' => '',
            'message_placeholder' => '',
            'submit_label' => '',
            'show_map' => false,
            'map_embed_url' => '',
        ];

        $out = [];
        foreach ($defaults as $key => $default) {
            if ($key === 'show_map') {
                $out[$key] = array_key_exists($key, $stored) ? (bool) $stored[$key] : (bool) $default;

                continue;
            }
            $val = $stored[$key] ?? null;
            $out[$key] = ($val !== null && $val !== '') ? $val : $default;
        }

        return $out;
    }

    /**
     * Public hero image URL for the contact layout (stored path, absolute URL, or gallery fallback).
     */
    public static function resolveHeroBackgroundUrl(?string $storedPath): ?string
    {
        $storedPath = $storedPath !== null ? trim($storedPath) : '';
        if ($storedPath !== '') {
            return filter_var($storedPath, FILTER_VALIDATE_URL)
                ? $storedPath
                : asset('storage/'.ltrim($storedPath, '/'));
        }

        $contactHeroImage = GalleryItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->value('image');

        if (! $contactHeroImage) {
            return null;
        }

        return filter_var($contactHeroImage, FILTER_VALIDATE_URL)
            ? $contactHeroImage
            : asset('storage/'.$contactHeroImage);
    }
}
