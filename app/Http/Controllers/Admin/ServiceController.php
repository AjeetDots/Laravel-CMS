<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Finish;
use App\Models\Service;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = Service::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['title', 'slug']);
        $services = $query->orderBy('sort_order')->get();

        return view('admin.services.index', compact('services'));
    }
    public function create() {
        $finishes = Finish::orderBy('title')->get();
        $defaultSortOrder = AdminDefaultSortOrder::next(Service::class);

        return view('admin.services.form', [
            'service' => new Service(),
            'finishes' => $finishes,
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }
    public function store(StoreServiceRequest $request) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        if (isset($data['features'])) {
            $data['features'] = array_values(array_filter($data['features']));
        }
        $service = Service::create($data);
        $service->saveSeo($request->input('seo', []));
        $service->finishes()->sync($request->input('finish_ids', []));
        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }
    public function edit(Service $service) {
        $service->load(['seoMeta', 'finishes']);
        $finishes = Finish::orderBy('title')->get();

        return view('admin.services.form', [
            'service' => $service,
            'finishes' => $finishes,
            'defaultSortOrder' => null,
        ]);
    }
    public function update(UpdateServiceRequest $request, Service $service) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        if (isset($data['features'])) {
            $data['features'] = array_values(array_filter($data['features']));
        }
        $service->update($data);
        $service->saveSeo($request->input('seo', []));
        $service->finishes()->sync($request->input('finish_ids', []));
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }
    public function destroy(Service $service) {
        $service->delete();
        return back()->with('success', 'The service has been removed.');
    }
    public function show(Service $service) {
        return redirect()->route('admin.services.edit', $service);
    }
}
