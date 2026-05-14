<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Http\Requests\Admin\UpdateSliderRequest;
use App\Models\Slider;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = Slider::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title', 'subtitle']);
        $sliders = $query->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.sliders.index', compact('sliders'));
    }
    public function create() {
        $defaultSortOrder = AdminDefaultSortOrder::next(Slider::class);

        return view('admin.sliders.form', [
            'slider' => new Slider(),
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }
    public function store(StoreSliderRequest $request) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        Slider::create($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created.');
    }
    public function edit(Slider $slider) {
        return view('admin.sliders.form', [
            'slider' => $slider,
            'defaultSortOrder' => null,
        ]);
    }
    public function update(UpdateSliderRequest $request, Slider $slider) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($slider->image) Storage::disk('public')->delete($slider->image);
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        $slider->update($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }
    public function destroy(Slider $slider) {
        $slider->delete();
        return back()->with('success', 'The slide has been removed.');
    }
    public function show(Slider $slider) {
        return redirect()->route('admin.sliders.edit', $slider);
    }
}
