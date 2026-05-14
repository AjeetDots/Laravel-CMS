<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioPageContent extends Model
{
    protected $fillable = [
        'page_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const PAGE_KEY_LISTING = 'portfolio_listing';

    /**
     * @return array<string, mixed>
     */
    public static function listingDataWithDefaults(): array
    {
        $row = static::query()->where('page_key', static::PAGE_KEY_LISTING)->first();
        $stored = is_array($row?->data) ? $row->data : [];

        $defaults = [
            'page_title' => '',
            'intro_eyebrow' => '',
            'intro_title' => '',
            'intro_body' => '',
            'breadcrumb_current' => '',
            'filter_all_label' => '',
            'card_link_text' => '',
            'label_real_project' => '',
            'label_reference' => '',
            'empty_message' => '',
            'empty_btn_text' => '',
            'empty_btn_url' => '',
            'bottom_heading' => '',
            'bottom_body' => '',
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
