<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model {
    protected $fillable = ['title', 'image', 'category', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getImageUrlAttribute(): string {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) { return $this->image; }
        return asset('storage/' . $this->image);
    }
}
