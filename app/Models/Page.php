<?php
namespace App\Models;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model {
    use HasSeo;
    protected $fillable = ['title', 'slug', 'content', 'meta_title', 'meta_description', 'template', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    protected static function boot() {
        parent::boot();
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
