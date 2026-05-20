<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFinishRequest;
use App\Http\Requests\Admin\UpdateFinishRequest;
use App\Models\Finish;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class FinishController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = Finish::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title', 'slug']);
        $finishes = $query->orderBy('sort_order')->orderBy('title')->get();

        return view('admin.finishes.index', compact('finishes'));
    }

    public function create() {
        $defaultSortOrder = AdminDefaultSortOrder::next(Finish::class);

        return view('admin.finishes.form', [
            'finish' => new Finish(),
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }

    public function store(StoreFinishRequest $request) {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('finishes', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('finishes/gallery', 'public');
            }
        }
        $data['gallery'] = $gallery ?: null;
        $data['tags']    = $this->parseTags($request->input('tags_raw'));

        $finish = Finish::create($data);
        $finish->saveSeo($request->input('seo', []));

        return redirect()->route('admin.finishes.index')->with('success', 'Finish created.');
    }

    public function edit(Finish $finish) {
        $finish->load('seoMeta');

        return view('admin.finishes.form', [
            'finish' => $finish,
            'defaultSortOrder' => null,
        ]);
    }

    public function update(UpdateFinishRequest $request, Finish $finish) {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            if ($finish->cover_image) Storage::disk('public')->delete($finish->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('finishes', 'public');
        }

        $gallery = $finish->gallery ?? [];
        if ($request->boolean('clear_gallery')) {
            foreach ($gallery as $old) Storage::disk('public')->delete($old);
            $gallery = [];
        }
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('finishes/gallery', 'public');
            }
        }
        $data['gallery'] = $gallery ?: null;
        $data['tags']    = $this->parseTags($request->input('tags_raw'));

        $finish->update($data);
        $finish->saveSeo($request->input('seo', []));

        return redirect()->route('admin.finishes.index')->with('success', 'Finish updated.');
    }

    public function destroy(Finish $finish) {
        $finish->delete();
        return back()->with('success', 'The finish has been removed.');
    }

    public function show(Finish $finish) {
        return redirect()->route('admin.finishes.edit', $finish);
    }

    private function parseTags(?string $raw): ?array {
        if (!$raw) return null;
        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }
}
