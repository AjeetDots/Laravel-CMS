<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller {
    public function index() {
        $pages = Page::latest()->get();
        return view('admin.pages.index', compact('pages'));
    }
    public function create() {
        return view('admin.pages.form', ['page' => new Page(), 'templates' => $this->templates()]);
    }
    public function store(StorePageRequest $request) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        Page::create($data);
        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }
    public function edit(Page $page) {
        return view('admin.pages.form', compact('page') + ['templates' => $this->templates()]);
    }
    public function update(UpdatePageRequest $request, Page $page) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $page->update($data);
        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }
    public function destroy(Page $page) {
        $page->delete();
        return back()->with('success', 'Page deleted.');
    }
    public function show(Page $page) {
        return redirect()->route('admin.pages.edit', $page);
    }
    private function templates(): array {
        return [
            'default'    => 'Default',
            'about'      => 'About Page (Editorial)',
            'full-width' => 'Full Width',
            'sidebar'    => 'With Sidebar',
        ];
    }
}
