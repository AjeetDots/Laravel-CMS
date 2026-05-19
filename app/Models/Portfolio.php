<?php
namespace App\Models;
use App\Support\CmsImage;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Portfolio extends Model {
    use HasSeo;
    use SoftDeletes;

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

    public function getCoverImageUrlAttribute(): string {
        return CmsImage::resolve($this->cover_image);
    }

    /** Alias for HasSeo::getSeoImage() fallback. */
    public function getImageUrlAttribute(): string {
        return $this->cover_image_url;
    }

    public function getGalleryUrlsAttribute(): array {
        return CmsImage::resolveMany($this->gallery ?? []);
    }

    public function getProjectTypeLabelAttribute(): string {
        return $this->project_type === 'real' ? 'Real Project' : 'Reference';
    }
}
