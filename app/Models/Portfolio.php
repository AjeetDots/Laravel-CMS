<?php
namespace App\Models;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Portfolio extends Model {
    use HasSeo;

    protected $fillable = [
        'title', 'slug', 'description', 'project_type',
        'cover_image', 'gallery', 'tags', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gallery'   => 'array',
        'tags'      => 'array',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = Str::slug($portfolio->title);
            }
        });
    }

    public function getCoverImageUrlAttribute(): ?string {
        if (!$this->cover_image) return null;
        return filter_var($this->cover_image, FILTER_VALIDATE_URL)
            ? $this->cover_image
            : asset('storage/' . $this->cover_image);
    }

    /** Alias for HasSeo::getSeoImage() fallback. */
    public function getImageUrlAttribute(): ?string {
        return $this->cover_image_url;
    }

    public function getGalleryUrlsAttribute(): array {
        return collect($this->gallery ?? [])->map(function ($path) {
            return filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $path);
        })->toArray();
    }

    public function getProjectTypeLabelAttribute(): string {
        return $this->project_type === 'real' ? 'Real Project' : 'Reference';
    }
}
