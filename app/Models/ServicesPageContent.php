<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesPageContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY_LISTING = 'services_listing';

    /**
     * @return array<string, mixed>
     */
    public static function listingDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY_LISTING)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'intro_eyebrow' => 'Services',
            'intro_title' => "Three disciplines,\napplied with the\nsame obsession.",
            'intro_body' => 'From a single feature wall to a full residence, we work alongside designers, architects and private clients to deliver finishes of lasting beauty.',
            'service_cta_prefix' => 'Enquire about',
            'empty_message' => 'No services available yet.',
            'empty_btn_text' => 'Get in touch',
            'empty_btn_url' => '',
            'bottom_eyebrow' => 'BEGIN',
            'bottom_heading' => "Bring your space.\nWe'll bring the finish.",
            'bottom_body' => '',
            'bottom_btn_text' => 'Get in touch',
            'bottom_btn_url' => '',
        ];

        $out = [];
        foreach ($defaults as $key => $default) {
            $val = $stored[$key] ?? null;
            $out[$key] = ($val !== null && $val !== '') ? $val : $default;
        }

        return $out;
    }
}
