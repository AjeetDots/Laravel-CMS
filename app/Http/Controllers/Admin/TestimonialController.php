<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = Testimonial::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['client_name', 'client_company', 'message']);
        $testimonials = $query->orderBy('sort_order')->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }
    public function create() {
        $defaultSortOrder = AdminDefaultSortOrder::next(Testimonial::class);

        return view('admin.testimonials.form', [
            'testimonial' => new Testimonial(),
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }
    public function store(StoreTestimonialRequest $request) {
        $data = $request->validated();
        if ($request->hasFile('client_image')) {
            $data['client_image'] = $request->file('client_image')->store('testimonials', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        Testimonial::create($data);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }
    public function edit(Testimonial $testimonial) {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'defaultSortOrder' => null,
        ]);
    }
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial) {
        $data = $request->validated();
        if ($request->hasFile('client_image')) {
            if ($testimonial->client_image) Storage::disk('public')->delete($testimonial->client_image);
            $data['client_image'] = $request->file('client_image')->store('testimonials', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        $testimonial->update($data);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }
    public function destroy(Testimonial $testimonial) {
        $testimonial->delete();
        return back()->with('success', 'The testimonial has been removed.');
    }
    public function show(Testimonial $testimonial) {
        return redirect()->route('admin.testimonials.edit', $testimonial);
    }
}
