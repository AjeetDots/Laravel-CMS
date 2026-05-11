<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Support\FrontendViewCache;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        return view('admin.menus.index', compact('menus'));
    }
    public function create() {
        $parents = Menu::whereNull('parent_id')->orderBy('sort_order')->get();
        return view('admin.menus.form', ['menu' => new Menu(), 'parents' => $parents]);
    }
    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        Menu::create($data);
        FrontendViewCache::forgetNavMenus();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item created.');
    }
    public function edit(Menu $menu) {
        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('sort_order')->get();
        return view('admin.menus.form', compact('menu', 'parents'));
    }
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $menu->update($data);
        FrontendViewCache::forgetNavMenus();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item updated.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        FrontendViewCache::forgetNavMenus();

        return back()->with('success', 'Menu item deleted.');
    }
    public function show(Menu $menu) {
        return redirect()->route('admin.menus.edit', $menu);
    }
}
