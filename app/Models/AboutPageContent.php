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
            'intro_eyebrow' => 'About the atelier',
            'intro_title' => "A studio of artisans,\na craft of patience.",
            'story_heading' => 'Our story',
            'story_body_1' => 'Trained in the lime-plaster traditions of Venice and refined across two decades of private and commercial commissions, our team has quietly built a reputation for finishes of unusual depth and consistency.',
            'story_body_2' => 'We work closely with leading interior designers and architects, and have been entrusted with environments for film, television and editorial productions where the surface itself must perform under the lens.',
            'story_body_3' => 'Every project begins with the room - its light, proportions, and intent - and ends with a finish made by hand.',
            'image_main' => null,
            'image_accent' => null,
            'image_studio' => null,
            'image_main_alt' => 'Bespoke interior finish',
            'image_accent_alt' => 'Signature polished finish',
            'image_studio_alt' => 'Workshop and studio finish development',
            'stat1_num' => '20+',
            'stat1_label' => 'Years of practice',
            'stat2_num' => '300+',
            'stat2_label' => 'Private commissions',
            'stat3_num' => '40+',
            'stat3_label' => 'Productions worked on',
            'workshop_eyebrow' => 'Workshop & studio',
            'workshop_heading' => 'Where the work begins.',
            'workshop_body' => 'Samples, mock-ups and bespoke profiles are developed at our London studio before being installed on site by our master artisans.',
            'workshop_btn_text' => 'Visit the studio',
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
