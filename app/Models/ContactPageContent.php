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

    /**
     * @return array<string, mixed>
     */
    public static function viewDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'page_title' => '',
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
            'subject_default' => '',
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
}
