<?php
namespace App\Models;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Finish extends Model {
    use HasSeo;

    protected $fillable = [
        'title', 'slug', 'description', 'use_cases',
        'cover_image', 'gallery', 'tags', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gallery'   => 'array',
        'tags'      => 'array',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($finish) {
            if (empty($finish->slug)) {
                $finish->slug = Str::slug($finish->title);
            }
        });
    }

    public function getCoverImageUrlAttribute(): ?string {
        if (!$this->cover_image) return null;
        return filter_var($this->cover_image, FILTER_VALIDATE_URL)
            ? $this->cover_image
            : asset('storage/' . $this->cover_image);
    }

    /**
     * Card / grid image: cover first, else first gallery file (so home & listings show a real photo when only gallery was uploaded).
     */
    public function getThumbnailUrlAttribute(): ?string {
        if ($this->cover_image_url) {
            return $this->cover_image_url;
        }
        $g = $this->gallery_urls;
        return $g[0] ?? null;
    }

    /** Alias for HasSeo::getSeoImage() fallback (cover-based models). */
    public function getImageUrlAttribute(): ?string {
        return $this->cover_image_url ?? ($this->gallery_urls[0] ?? null);
    }

    public function getGalleryUrlsAttribute(): array {
        return collect($this->gallery ?? [])->map(function ($path) {
            return filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $path);
        })->toArray();
    }

    public function services() {
        return $this->belongsToMany(Service::class, 'finish_service');
    }
}
