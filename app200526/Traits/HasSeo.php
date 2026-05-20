<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Create or update the SEO record for this model.
     * Skips save if all values are blank (nothing to persist).
     */
    public function saveSeo(array $data): void
    {
        $payload = array_filter($data, fn($v) => $v !== null && $v !== '');

        if (empty($payload)) {
            return;
        }

        $this->seoMeta()->updateOrCreate([], $data);
    }

    // ── Fallback getters used by the frontend meta-tags partial ──────────────

    public function getSeoTitle(): string
    {
        return $this->seoMeta?->meta_title
            ?: ($this->meta_title ?? null)          // Page model column
            ?: ($this->title ?? '')
            ?: config('app.name');
    }

    public function getSeoDescription(): string
    {
        return $this->seoMeta?->meta_description
            ?: ($this->meta_description ?? null)    // Page model column
            ?: ($this->excerpt ?? null)             // BlogPost column
            ?: ($this->short_description ?? null)   // Service column
            ?: '';
    }

    public function getSeoImage(): ?string
    {
        return $this->seoMeta?->og_image
            ?: ($this->image_url ?? null)
            ?: null;
    }

    public function getSeoCanonical(): string
    {
        return $this->seoMeta?->canonical_url ?: url()->current();
    }

    public function getSeoRobots(): string
    {
        return $this->seoMeta
            ? (($this->seoMeta->robots_index ?? 'index') . ', ' . ($this->seoMeta->robots_follow ?? 'follow'))
            : 'index, follow';
    }

    public function getSeoKeyword(): string
    {
        return $this->seoMeta?->focus_keyword ?? '';
    }
}
