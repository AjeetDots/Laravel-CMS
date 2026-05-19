<?php
namespace App\Models;
use App\Support\CmsImage;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Finish extends Model {
    use HasSeo;
    use SoftDeletes;

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
        static::deleting(function (Finish $finish) {
            if ($finish->isForceDeleting()) {
                return;
            }
            $finish->services()->detach();
        });
    }

    public function getCoverImageUrlAttribute(): string {
        return CmsImage::resolve($this->cover_image);
    }

    /**
     * Card / grid image: cover first, else first gallery file (so home & listings show a real photo when only gallery was uploaded).
     */
    public function getThumbnailUrlAttribute(): string {
        if (filled($this->cover_image)) {
            return $this->cover_image_url;
        }

        $firstGallery = ($this->gallery ?? [])[0] ?? null;

        return CmsImage::resolve(is_string($firstGallery) ? $firstGallery : null);
    }

    /** Alias for HasSeo::getSeoImage() fallback (cover-based models). */
    public function getImageUrlAttribute(): string {
        return $this->thumbnail_url;
    }

    public function getGalleryUrlsAttribute(): array {
        return CmsImage::resolveMany($this->gallery ?? []);
    }

    public function services() {
        return $this->belongsToMany(Service::class, 'finish_service');
    }
}
