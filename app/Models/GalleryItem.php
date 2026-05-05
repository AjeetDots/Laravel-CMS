<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model {
    protected $fillable = ['title', 'image', 'gallery_category_id', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function galleryCategory(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class);
    }

    public function getImageUrlAttribute(): string {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) { return $this->image; }
        return asset('storage/' . $this->image);
    }
}
