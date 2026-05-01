<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller {
    public function index() {
        $services = Service::orderBy('sort_order')->get();
        return view('admin.services.index', compact('services'));
    }
    public function create() {
        return view('admin.services.form', ['service' => new Service()]);
    }
    public function store(StoreServiceRequest $request) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }
    public function edit(Service $service) {
        return view('admin.services.form', compact('service'));
    }
    public function update(UpdateServiceRequest $request, Service $service) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);
        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }
    public function destroy(Service $service) {
        if ($service->image) Storage::disk('public')->delete($service->image);
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }
    public function show(Service $service) {
        return redirect()->route('admin.services.edit', $service);
    }
}
