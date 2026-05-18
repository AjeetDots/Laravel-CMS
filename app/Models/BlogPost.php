<?php
namespace App\Models;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model {
    use HasSeo;
    use SoftDeletes;
    protected $fillable = ['title','slug','category','category_id','author','excerpt','content','image','is_active','published_at','sort_order'];
    protected $casts = ['is_active' => 'boolean', 'published_at' => 'datetime'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function postCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->resolvedCategoryLabel();
    }

    /**
     * Category display name (related Category or legacy string column).
     */
    public function resolvedCategoryLabel(): string
    {
        if ($this->category_id) {
            $related = $this->relationLoaded('postCategory')
                ? $this->getRelation('postCategory')
                : null;

            if ($related && filled($related->name)) {
                return (string) $related->name;
            }

            $name = Category::query()->whereKey($this->category_id)->value('name');
            if (filled($name)) {
                return (string) $name;
            }
        }

        return trim((string) ($this->attributes['category'] ?? ''));
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) { return null; }
        if (filter_var($this->image, FILTER_VALIDATE_URL)) { return $this->image; }
        return asset('storage/' . $this->image);
    }

    public function getReadingTimeAttribute(): string
    {
        $words   = str_word_count(strip_tags($this->content ?? ''));
        $minutes = max(1, ceil($words / 200));
        return $minutes . ' min read';
    }
}
