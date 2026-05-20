<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY_LISTING = 'about_page';

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
            'story_heading' => '',
            'story_body_1' => '',
            'story_body_2' => '',
            'story_body_3' => '',
            'image_main' => null,
            'image_accent' => null,
            'image_studio' => null,
            'image_main_alt' => '',
            'image_accent_alt' => '',
            'image_studio_alt' => '',
            'stat1_num' => '',
            'stat1_label' => '',
            'stat2_num' => '',
            'stat2_label' => '',
            'stat3_num' => '',
            'stat3_label' => '',
            'workshop_eyebrow' => '',
            'workshop_heading' => '',
            'workshop_body' => '',
            'workshop_btn_text' => '',
            'workshop_btn_url' => '',
        ];

        $imageKeys = ['image_main', 'image_accent', 'image_studio'];
        $out = [];
        foreach ($defaults as $key => $default) {
            $val = $stored[$key] ?? null;
            if (in_array($key, $imageKeys, true)) {
                $out[$key] = ($val !== null && $val !== '') ? $val : null;

                continue;
            }
            $out[$key] = ($val !== null && $val !== '') ? $val : $default;
        }

        return $out;
    }
}
