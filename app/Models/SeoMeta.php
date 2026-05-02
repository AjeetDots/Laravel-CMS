<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'meta_title', 'meta_description', 'focus_keyword',
        'canonical_url', 'robots_index', 'robots_follow',
        'og_title', 'og_description', 'og_image',
        'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
        'schema_markup',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Convenience: "index, follow" robots string for meta tag output. */
    public function getRobotsStringAttribute(): string
    {
        return ($this->robots_index ?? 'index') . ', ' . ($this->robots_follow ?? 'follow');
    }
}
