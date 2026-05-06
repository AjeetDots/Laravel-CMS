<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->get();
        return view('admin.pages.index', compact('pages'));
    }
    public function create()
    {
        return view('admin.pages.form', ['page' => new Page(), 'templates' => $this->templates()]);
    }
    public function store(StorePageRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        unset($data['sections']);
        $page = Page::create($data);
        foreach($request->sections ?? [] as $index => $section){
            $image = null;
            if(isset($section['image'])){
                $image =
                    $section['image']
                    ->store(
                        'pages',
                        'public'
                    );
            }

            $page->sections()->create([
                'type' =>
                    $section['type'],
                'position' =>
                    $index + 1,
                'data' => [
                    'title' =>
                        $section['title'],
                    'content' =>
                        $section['content'],
                    'image' =>
                        $image,
                    'image_position' =>
                        $section[
                            'image_position'
                        ],
                ]
            ]);
        }
        $page->saveSeo($request->input('seo', []));
        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }
    public function edit(Page $page)
    {
        $page->load(['seoMeta','sections']);
        return view('admin.pages.form', compact('page') + ['templates' => $this->templates()]);
    }
    public function update(UpdatePageRequest $request, Page $page)
    {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        unset($data['sections']);
        $page->update($data);
        $page->sections()->delete();
        foreach($request->sections ?? [] as $index => $section){
                    $image = $section['existing_image'] ??  null;
                    if(isset($section['image'])){
                        $image =
                            $section['image']
                            ->store(
                                'pages',
                                'public'
                            );
                    }

                    $page->sections()
                        ->create([
                            'type' =>
                                $section['type'],

                            'position' =>
                                $index + 1,

                            'data' => [
                                'title' =>
                                    $section['title'],
                                'content' =>
                                    $section['content'],
                                'image' =>
                                    $image,
                                'image_position' =>
                                    $section[
                                        'image_position'
                                    ],
                            ]
                        ]);
                }
        $page->saveSeo($request->input('seo', []));
        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }
    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted.');
    }
    public function show(Page $page)
    {
        return redirect()->route('admin.pages.edit', $page);
    }
    private function templates(): array
    {
        return [
            'default'    => 'Default',
            'about'      => 'About Page (Editorial)',
            'full-width' => 'Full Width',
            'sidebar'    => 'With Sidebar',
        ];
    }
}
