<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use App\Support\AdminDefaultSortOrder;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = Brand::query();
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['name']);
        $brands = $query->orderBy('sort_order')->get();

        return view('admin.brands.index', compact('brands'));
    }
    public function create() {
        $defaultSortOrder = AdminDefaultSortOrder::next(Brand::class);

        return view('admin.brands.form', [
            'brand' => new Brand(),
            'defaultSortOrder' => $defaultSortOrder,
        ]);
    }
    public function store(StoreBrandRequest $request) {
        $data = $request->validated();
        $data['logo'] = $request->file('logo')->store('brands', 'public');
        $data['is_active'] = $request->boolean('is_active');
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }
    public function edit(Brand $brand) {
        return view('admin.brands.form', [
            'brand' => $brand,
            'defaultSortOrder' => null,
        ]);
    }
    public function update(UpdateBrandRequest $request, Brand $brand) {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            if ($brand->logo) Storage::disk('public')->delete($brand->logo);
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }
    public function destroy(Brand $brand) {
        $brand->delete();
        return back()->with('success', 'The brand has been removed.');
    }
}
