<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model {
    protected $fillable = ['title','slug','category','author','excerpt','content','image','is_active','published_at','sort_order'];
    protected $casts = ['is_active' => 'boolean', 'published_at' => 'datetime'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function getImageUrlAttribute(): ?string {
        if (!$this->image) return null;
        if (filter_var($this->image, FILTER_VALIDATE_URL)) return $this->image;
        return asset('storage/' . $this->image);
    }

    public function getReadingTimeAttribute(): string {
        $words = str_word_count(strip_tags($this->content ?? ''));
        $minutes = max(1, ceil($words / 200));
        return $minutes . ' min read';
    }
}
