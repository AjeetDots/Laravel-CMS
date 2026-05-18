<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;

class PageController extends Controller
{
    use AppliesAdminTableFilters;

    public function index()
    {
        $query = Page::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title', 'slug']);
        $pages = $query->latest()->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.form', [
            'page' => new Page(['template' => Page::defaultTemplate()]),
            'templates' => $this->templatesForForm(new Page),
        ]);
    }

    public function store(StorePageRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        unset($data['sections']);
        $page = Page::create($data);
        if (in_array($page->template, Page::sectionedTemplates(), true)) {
            foreach ($request->sections ?? [] as $index => $section) {
            $image = null;
            if (isset($section['image'])) {
                $image =
                    $section['image']
                        ->store(
                            'pages',
                            'public'
                        );
            }

            $page->sections()->create([
                'type' => $section['type'] ?? 'media_content',

                'position' => $index + 1,
                'data' => [
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'image' => $image,
                    'image_position' => $this->normalizeSectionImagePosition($section['image_position'] ?? null),
                    'buttons' => $section['buttons']
                        ?? [],
                ],
            ]);
            }
        }
        $page->saveSeo($request->input('seo', []));

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        $page->load(['seoMeta', 'sections']);

        return view('admin.pages.form', compact('page') + ['templates' => $this->templatesForForm($page)]);
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        unset($data['sections']);
        if (($data['template'] ?? '') === 'about') {
            $data['content'] = null;
            $data['body_order'] = Page::BODY_ORDER_CONTENT_FIRST;
            $data['sidebar_content'] = null;
            $data['sidebar_cta_title'] = null;
            $data['sidebar_cta_text'] = null;
        }
        if (($data['template'] ?? '') === Page::TEMPLATE_CONTACT) {
            $data['body_order'] = Page::BODY_ORDER_CONTENT_FIRST;
            $data['sidebar_content'] = null;
            $data['sidebar_cta_title'] = null;
            $data['sidebar_cta_text'] = null;
        }
        $page->update($data);
        $page->sections()->delete();
        if ($page->template === 'about') {
            $page->saveSeo($request->input('seo', []));

            return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
        }
        if ($page->template === Page::TEMPLATE_CONTACT) {
            $page->saveSeo($request->input('seo', []));

            return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
        }
        foreach ($request->sections ?? [] as $index => $section) {
            $image = $section['existing_image'] ?? null;
            if (isset($section['image'])) {
                $image =
                    $section['image']
                        ->store(
                            'pages',
                            'public'
                        );
            }
            $buttons = collect($section['buttons'] ?? [])->filter(
                function ($button) {
                    return
                        ! empty(
                            $button['text']
                        )
                        &&
                        ! empty(
                            $button['link']
                        );
                }
            )->values()->toArray();

            $page->sections()->create([
                'type' => $section['type'] ?? 'media_content',
                'position' => $index + 1,
                'data' => [
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'image' => $image,
                    'image_position' => $this->normalizeSectionImagePosition($section['image_position'] ?? null),
                    'buttons' => $buttons,
                    // $section['buttons']
                    // ?? []
                ],
            ]);
        }
        $page->saveSeo($request->input('seo', []));

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        if ($page->isDeletionProtected()) {
            return back()->with('error', 'This page is tied to core site URLs or legal content and cannot be deleted.');
        }
        $page->delete();

        return back()->with('success', 'The page has been removed.');
    }

    public function show(Page $page)
    {
        return redirect()->route('admin.pages.edit', $page);
    }

    private function templates(): array
    {
        return [
            'about' => 'About Page (Editorial)',
            Page::TEMPLATE_DEFAULT => 'Default',
            Page::TEMPLATE_FULL_WIDTH => 'Full Width',
            Page::TEMPLATE_SIDEBAR => 'With Sidebar',
            Page::TEMPLATE_CONTACT => 'Contact (theme content and form)',
        ];
    }

    private function templatesForForm(Page $page): array
    {
        $slug = $page->exists
            ? (string) $page->slug
            : '';

        $allowed = Page::allowedTemplatesForSlug($slug);

        return array_intersect_key($this->templates(), array_flip($allowed));
    }

    private function normalizeSectionImagePosition(mixed $value): string
    {
        $v = strtolower(trim((string) ($value ?? '')));

        return $v === 'right' ? 'right' : 'left';
    }
}
