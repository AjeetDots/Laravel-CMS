<?php
namespace App\Models;
use App\Support\CmsImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryItem extends Model {
    use SoftDeletes;
    protected $fillable = ['title', 'section_content', 'image', 'gallery_category_id', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function galleryCategory(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class);
    }

    public function getImageUrlAttribute(): string {
        return CmsImage::resolve($this->image);
    }
}
