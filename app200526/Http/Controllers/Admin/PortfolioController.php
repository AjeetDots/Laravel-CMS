<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePortfolioRequest;
use App\Http\Requests\Admin\UpdatePortfolioRequest;
use App\Models\Portfolio;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    use AppliesAdminTableFilters;

    public function index()
    {
        $query = Portfolio::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title', 'slug']);
        $this->applyProjectType($query, request('project_type'));
        $portfolios = $query->orderBy('sort_order')->orderBy('title')->get();

        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolio.form', [
            'portfolio' => new Portfolio,
            'defaultSortOrder' => AdminDefaultSortOrder::next(Portfolio::class),
        ]);
    }

    public function store(StorePortfolioRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('portfolio', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('portfolio/gallery', 'public');
            }
        }
        $data['gallery'] = $gallery ?: null;
        $data['tags'] = $this->parseTags($request->input('tags_raw'));

        $portfolio = Portfolio::create($data);
        $portfolio->saveSeo($request->input('seo', []));

        return redirect()->route('admin.portfolio.index')->with('success', 'Project created.');
    }

    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('seoMeta');

        return view('admin.portfolio.form', [
            'portfolio' => $portfolio,
            'defaultSortOrder' => null,
        ]);
    }

    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            if ($portfolio->cover_image) {
                Storage::disk('public')->delete($portfolio->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('portfolio', 'public');
        }

        $gallery = $portfolio->gallery ?? [];
        if ($request->boolean('clear_gallery')) {
            foreach ($gallery as $old) {
                Storage::disk('public')->delete($old);
            }
            $gallery = [];
        }
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('portfolio/gallery', 'public');
            }
        }
        $data['gallery'] = $gallery ?: null;
        $data['tags'] = $this->parseTags($request->input('tags_raw'));

        $portfolio->update($data);
        $portfolio->saveSeo($request->input('seo', []));

        return redirect()->route('admin.portfolio.index')->with('success', 'Project updated.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return back()->with('success', 'The project has been removed.');
    }

    public function show(Portfolio $portfolio)
    {
        return redirect()->route('admin.portfolio.edit', $portfolio);
    }

    /** @param  'reference'|'real'|''|null  $projectType */
    private function applyProjectType($query, ?string $projectType): void
    {
        if ($projectType === null || $projectType === '') {
            return;
        }

        if (in_array($projectType, ['reference', 'real'], true)) {
            $query->where('project_type', $projectType);
        }
    }

    private function parseTags(?string $raw): ?array
    {
        if (! $raw) {
            return null;
        }

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }
}
