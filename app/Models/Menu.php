<?php

namespace App\Models;

use App\Support\NavLinkPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'label', 'url', 'target', 'parent_id', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::deleting(function (Menu $menu) {
            if ($menu->isForceDeleting()) {
                return;
            }
            static::query()->where('parent_id', $menu->id)->each(fn (Menu $child) => $child->delete());
        });
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Top-level nav active state. Home must not use request()->is('*') (from empty ltrim('/') + '*').
     */
    public function isActiveForNav(): bool
    {
        foreach ($this->children as $child) {
            if ($child->matchesCurrentPath()) {
                return true;
            }
        }

        return $this->matchesCurrentPath();
    }

    public function matchesCurrentPath(): bool
    {
        return NavLinkPath::matchesCurrentPath($this->url ?? null);
    }
}
