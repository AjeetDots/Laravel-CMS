<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model {
    protected $fillable = ['title', 'slug', 'short_description', 'description', 'image', 'icon', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    protected static function boot() {
        parent::boot();
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }
    public function getImageUrlAttribute(): ?string {
        if (!$this->image) { return null; }
        return filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : asset('storage/' . $this->image);
    }
}
