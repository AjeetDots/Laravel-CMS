<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Http\Requests\Admin\UpdateSliderRequest;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller {
    public function index() {
        $sliders = Slider::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.sliders.index', compact('sliders'));
    }
    public function create() {
        return view('admin.sliders.form', ['slider' => new Slider()]);
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
        return view('admin.sliders.form', compact('slider'));
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
        if ($slider->image) Storage::disk('public')->delete($slider->image);
        $slider->delete();
        return back()->with('success', 'Slider deleted.');
    }
    public function show(Slider $slider) {
        return redirect()->route('admin.sliders.edit', $slider);
    }
}
