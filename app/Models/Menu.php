<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model {
    protected $fillable = ['name', 'label', 'url', 'target', 'parent_id', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function children(): HasMany {
        return $this->hasMany(Menu::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }
    public function parent(): BelongsTo {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
