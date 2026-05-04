<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePortfolioRequest;
use App\Http\Requests\Admin\UpdatePortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller {

    public function index() {
        $portfolios = Portfolio::orderBy('sort_order')->orderBy('title')->get();
        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create() {
        return view('admin.portfolio.form', ['portfolio' => new Portfolio()]);
    }

    public function store(StorePortfolioRequest $request) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
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
        $data['tags']    = $this->parseTags($request->input('tags_raw'));

        $portfolio = Portfolio::create($data);
        $portfolio->saveSeo($request->input('seo', []));

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item created.');
    }

    public function edit(Portfolio $portfolio) {
        $portfolio->load('seoMeta');
        return view('admin.portfolio.form', compact('portfolio'));
    }

    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            if ($portfolio->cover_image) Storage::disk('public')->delete($portfolio->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('portfolio', 'public');
        }

        $gallery = $portfolio->gallery ?? [];
        if ($request->boolean('clear_gallery')) {
            foreach ($gallery as $old) Storage::disk('public')->delete($old);
            $gallery = [];
        }
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('portfolio/gallery', 'public');
            }
        }
        $data['gallery'] = $gallery ?: null;
        $data['tags']    = $this->parseTags($request->input('tags_raw'));

        $portfolio->update($data);
        $portfolio->saveSeo($request->input('seo', []));

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item updated.');
    }

    public function destroy(Portfolio $portfolio) {
        if ($portfolio->cover_image) Storage::disk('public')->delete($portfolio->cover_image);
        foreach ($portfolio->gallery ?? [] as $img) Storage::disk('public')->delete($img);
        $portfolio->delete();
        return back()->with('success', 'Portfolio item deleted.');
    }

    public function show(Portfolio $portfolio) {
        return redirect()->route('admin.portfolio.edit', $portfolio);
    }

    private function parseTags(?string $raw): ?array {
        if (!$raw) return null;
        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }
}
