<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishesPageContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY_LISTING = 'finishes_listing';

    /**
     * @return array<string, mixed>
     */
    public static function listingDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY_LISTING)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'intro_eyebrow' => 'Our finishes',
            'intro_title' => 'Six finishes. One obsession with the surface.',
            'intro_body' => 'Every finish is mixed, applied and polished by hand. Bespoke colours are developed in studio against samples of your space, your light and your interiors.',
            'card_label_fallback' => 'Hand-crafted decorative finish',
            'empty_message' => 'No finishes have been published yet.',
            'empty_btn_text' => 'Get in touch',
            'empty_btn_url' => null,
            'bottom_eyebrow' => 'Begin',
            'bottom_heading' => 'Not sure which finish suits your space?',
            'bottom_body' => 'Tell us about the room and we\'ll prepare hand-made samples for your light.',
            'bottom_btn_text' => 'Request samples',
            'bottom_btn_url' => null,
        ];

        $out = [];
        foreach ($defaults as $key => $default) {
            $val = $stored[$key] ?? null;
            $out[$key] = ($val !== null && $val !== '') ? $val : $default;
        }

        return $out;
    }
}
