<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Setting;
use App\Support\FrontendViewCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Child views (e.g. frontend.contact) render with their own name before/independently of
        // the layout; they still need $settings and $navMenus. The layout also needs them.
        View::composer(['layouts.frontend', 'frontend.*'], function ($view) {
            static $shared = null;
            if ($shared !== null) {
                $view->with($shared);

                return;
            }

            try {
                $ttl = max(60, (int) config('cms.frontend_view_cache_ttl', 3600));

                $navMenus = Menu::query()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->with(['children' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }])
                    ->orderBy('sort_order')
                    ->get();

                $settingsRaw = Cache::remember(FrontendViewCache::SETTINGS_PLUCK_KEY, $ttl, function () {
                    return Setting::query()->pluck('value', 'key')->all();
                });
                $settings = collect(is_array($settingsRaw) ? $settingsRaw : []);
            } catch (\Exception $e) {
                $navMenus = collect();
                $settings = collect();
            }

            $shared = compact('navMenus', 'settings');
            $view->with($shared);
        });
    }
}
