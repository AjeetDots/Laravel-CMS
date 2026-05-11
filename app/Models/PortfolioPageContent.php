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
            'intro_eyebrow' => 'Completed work',
            'intro_title' => 'Portfolio',
            'intro_body' => 'Project-based inspiration — reference imagery and real commissions. Explore by tag or open a project for the full story.',
            'breadcrumb_current' => 'Portfolio',
            'filter_all_label' => 'All',
            'card_link_text' => 'View project',
            'label_real_project' => 'Real project',
            'label_reference' => 'Reference',
            'empty_message' => 'No portfolio entries yet.',
            'empty_btn_text' => '',
            'empty_btn_url' => '',
            'bottom_heading' => 'Planning something similar?',
            'bottom_body' => 'Share your brief and we\'ll outline timelines and options.',
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
