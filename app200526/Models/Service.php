<?php
namespace App\Models;
use App\Support\CmsImage;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model {
    use HasSeo;
    use SoftDeletes;
    protected $fillable = ['title', 'hover_title', 'slug', 'short_description', 'description', 'image', 'hover_image', 'icon', 'badge', 'features', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'features' => 'array'];
    protected static function boot() {
        parent::boot();
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
        static::deleting(function (Service $service) {
            if ($service->isForceDeleting()) {
                return;
            }
            $service->finishes()->detach();
        });
    }
    public function getImageUrlAttribute(): string {
        return CmsImage::resolve($this->image);
    }

    public function getHoverImageUrlAttribute(): string {
        return CmsImage::resolve($this->hover_image);
    }

    public function resolvedHoverTitle(): ?string
    {
        $hover = trim((string) ($this->hover_title ?? ''));

        return $hover !== '' ? $hover : null;
    }

    public function finishes() {
        return $this->belongsToMany(Finish::class, 'finish_service');
    }
}
