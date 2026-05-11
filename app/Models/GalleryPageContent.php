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
            'intro_eyebrow' => 'Portfolio',
            'intro_title' => 'A quiet record of recent work.',
            'filter_all_label' => 'All',
            'grid_category_fallback' => 'Portfolio',
            'empty_message' => 'No gallery items yet.',
            'empty_btn_text' => '',
            'empty_btn_url' => '',
            'bottom_heading' => 'Like what you see?',
            'bottom_btn_text' => 'Start a project',
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
