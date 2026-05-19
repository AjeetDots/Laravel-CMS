@extends('layouts.admin')

@section('title', isset($brand->id) ? 'Edit Brand' : 'Add Brand')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($brand->id) ? 'Edit Brand' : 'Add Brand' }}</h1>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($brand->id) ? route('admin.brands.update', $brand) : route('admin.brands.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($brand->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Brand Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website URL</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $brand->website) }}" placeholder="e.g. https://example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand->exists ? $brand->sort_order : ($defaultSortOrder ?? 1)) }}" min="1">
                        <div class="form-text">Must be unique among all brands.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Logo {{ isset($brand->id) ? '' : '*' }}</label>
                        <input type="file" name="logo" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                               @if(!isset($brand->id) || !$brand->logo) required @endif>
                        @if(isset($brand->id) && $brand->logo)
                            <div class="mt-2">
                                <img src="{{ Storage::disk('public')->exists($brand->logo) ? asset('storage/'.$brand->logo) : '' }}"
                                     class="img-preview" style="height:60px; width:100px; object-fit:contain;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($brand->id) ? 'Update Brand' : 'Create Brand' }}
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
