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
        $href = trim((string) ($this->url ?? ''));
        if ($href === '' || $href === '#') {
            return false;
        }

        $current = trim((string) request()->path(), '/');
        $menuPath = $this->normalizedPathFromHref($href);
        if ($menuPath === null) {
            return false;
        }

        // Home: only when URL is literally / and we are on the home route (never match other pages)
        if ($menuPath === '') {
            return $current === '' && request()->routeIs('home');
        }

        if ($menuPath === $current) {
            return true;
        }

        // e.g. menu /blog active on /blog/some-post (not using request()->is() — avoids wildcard quirks)
        return str_starts_with($current, $menuPath . '/');
    }

    /**
     * Path segment(s) for this link, without leading/trailing slashes. '' = site root (home).
     */
    private function normalizedPathFromHref(string $href): ?string
    {
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            $path = parse_url($href, PHP_URL_PATH);
            if ($path === null || $path === '' || $path === '/') {
                return '';
            }
            return trim($path, '/');
        }
        if ($href === '/') {
            return '';
        }
        return trim($href, '/');
    }
}
