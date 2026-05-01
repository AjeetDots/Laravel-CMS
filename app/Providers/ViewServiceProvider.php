<?php
namespace App\Providers;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {
    public function boot(): void {
        View::composer('*', function ($view) {
            try {
                $navMenus = Menu::whereNull('parent_id')
                    ->where('is_active', true)
                    ->with(['children' => function($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }])
                    ->orderBy('sort_order')
                    ->get();
                $settings = Setting::pluck('value', 'key');
            } catch (\Exception $e) {
                $navMenus = collect();
                $settings = collect();
            }
            $view->with(compact('navMenus', 'settings'));
        });
    }
}
