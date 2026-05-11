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
            'page_title' => 'Contact Us',
            'hero_line_1' => 'Bring us your space.',
            'hero_line_2' => "We'll bring the finish.",
            'hero_cta' => 'Get a quote',
            'hero_bg_image' => '',
            'info_eyebrow' => 'Contact',
            'info_heading_1' => "Let's discuss",
            'info_heading_2' => 'your project.',
            'info_lead' => 'Share a few details and a member of the studio will be in touch within one working day. For urgent enquiries, please call or message us on WhatsApp.',
            'studio_label' => 'Studio',
            'studio_body' => "Bespoke Ornate Plaster\nLondon, United Kingdom",
            'hours_label' => 'Hours',
            'hours_body' => "Monday - Friday\n09:00 - 18:00 GMT",
            'appointment_line' => 'By appointment',
            'fallback_phone_display' => '+1 (555) 123-4567',
            'fallback_whatsapp_label' => 'WhatsApp',
            'form_title' => 'Contact Us',
            'form_error_intro' => 'Please fix the errors below and resubmit.',
            'subject_default' => 'Website enquiry (contact)',
            'name_placeholder' => 'Your Name',
            'email_placeholder' => 'Email',
            'phone_field_label' => 'Phone (optional)',
            'national_placeholder' => 'Phone number',
            'message_placeholder' => 'Tell us about your space',
            'submit_label' => 'Send enquiry',
            'show_map' => true,
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19800.055036896083!2d0.0806371033339485!3d52.193834501271906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d870a027de5493%3A0x40fdbfa6f7c3e20!2sCambridge%2C%20UK!5e0!3m2!1sen!2sin!4v1778150755661!5m2!1sen!2sin',
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
