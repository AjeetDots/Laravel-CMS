<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Support\AdminDefaultSortOrder;
use App\Support\FrontendViewCache;
use App\Support\MenuLinkDirectory;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    use AppliesAdminTableFilters;

    public function index() {
        $query = Menu::whereNull('parent_id')->with('children');
        if (request()->has('status') && request('status') !== '' && request('status') !== null) {
            $query->where('is_active', (bool) (int) request('status'));
        }
        $term = trim((string) request('q', ''));
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(function ($w) use ($like) {
                $w->where('label', 'like', $like)
                    ->orWhere('name', 'like', $like)
                    ->orWhere('url', 'like', $like)
                    ->orWhereHas('children', function ($c) use ($like) {
                        $c->where(function ($cc) use ($like) {
                            $cc->where('label', 'like', $like)
                                ->orWhere('url', 'like', $like);
                        });
                    });
            });
        }
        $menus = $query->orderBy('sort_order')->get();

        return view('admin.menus.index', compact('menus'));
    }
    public function create() {
        $parents = Menu::whereNull('parent_id')->orderBy('sort_order')->get();
        $menuLinkGroups = MenuLinkDirectory::choiceGroups();
        $parentId = request()->filled('parent_id') ? (int) request('parent_id') : null;
        $defaultSortOrder = AdminDefaultSortOrder::next(Menu::class, ['parent_id' => $parentId]);

        return view('admin.menus.form', [
            'menu' => new Menu(),
            'parents' => $parents,
            'menuLinkGroups' => $menuLinkGroups,
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }
    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();
        unset($data['menu_link_mode']);
        $data['is_active'] = $request->boolean('is_active');
        $data['name'] = $this->menuInternalNameFromLabel($data['label']);
        Menu::create($data);
        FrontendViewCache::forgetNavMenus();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item created.');
    }
    public function edit(Menu $menu) {
        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('sort_order')->get();
        $menuLinkGroups = MenuLinkDirectory::choiceGroups();

        return view('admin.menus.form', [
            'menu' => $menu,
            'parents' => $parents,
            'menuLinkGroups' => $menuLinkGroups,
            'defaultSortOrder' => null,
        ]);
    }
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();
        unset($data['menu_link_mode']);
        $data['is_active'] = $request->boolean('is_active');
        $data['name'] = $this->menuInternalNameFromLabel($data['label']);
        $menu->update($data);
        FrontendViewCache::forgetNavMenus();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item updated.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        FrontendViewCache::forgetNavMenus();

        return back()->with('success', 'The menu item has been removed.');
    }
    public function show(Menu $menu) {
        return redirect()->route('admin.menus.edit', $menu);
    }

    /**
     * Internal key for the menus table (not shown in the admin form).
     */
    private function menuInternalNameFromLabel(string $label): string
    {
        $slug = Str::slug(trim($label));

        if ($slug === '') {
            $slug = 'nav-' . Str::lower(Str::random(8));
        }

        return Str::limit($slug, 100, '');
    }
}
