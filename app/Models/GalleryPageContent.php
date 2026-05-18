<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPageContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY_LISTING = 'gallery_listing';

    /**
     * @return array<string, mixed>
     */
    public static function listingDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY_LISTING)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'intro_eyebrow' => '',
            'intro_title' => '',
            'filter_all_label' => '',
            'grid_category_fallback' => '',
            'empty_message' => '',
            'empty_btn_text' => 'Get in touch',
            'empty_btn_url' => '',
            'bottom_heading' => '',
            'bottom_btn_text' => '',
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
