@extends('layouts.admin')

@section('title', isset($service->id) ? 'Edit Service' : 'Add Service')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($service->id) ? 'Edit Service' : 'Add Service' }}</h1>
    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($service->id) ? route('admin.services.update', $service) : route('admin.services.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($service->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $service->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $service->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description *</label>
                        <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description', $service->short_description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" class="form-control" rows="6">{{ old('description', $service->description) }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if(isset($service->id) && $service->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$service->image) }}" class="img-preview" style="height:80px;">
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="fas fa-code">
                        <div class="form-text">FontAwesome icon class e.g. <code>fas fa-code</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($service->id) ? 'Update Service' : 'Create Service' }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
