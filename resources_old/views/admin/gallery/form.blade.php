@extends('layouts.admin')

@section('title', isset($item->id) ? 'Edit Gallery Image' : 'Upload Gallery Image')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($item->id) ? 'Edit Image' : 'Upload Image' }}</h1>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($item->id) ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" placeholder="e.g. Optional title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="gallery_category_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ (string) old('gallery_category_id', $item->gallery_category_id ?? request('gallery_category_id')) === (string) $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Manage labels in <a href="{{ route('admin.gallery-categories.index') }}">Gallery categories</a>.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->exists ? $item->sort_order : ($defaultSortOrder ?? 0)) }}" min="0">
                        <div class="form-text">Must be unique within the same gallery category (including “uncategorised”).</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Image {{ isset($item->id) ? '' : '*' }}</label>
                        <input type="file" name="image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml" {{ isset($item->id) ? '' : 'required' }}>
                        @if(isset($item->id) && $item->image)
                            <div class="mt-2">
                                <img src="{{ Storage::disk('public')->exists($item->image) ? asset('storage/'.$item->image) : '' }}"
                                     class="img-preview" style="height:100px; width:140px; object-fit:cover;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($item->id) ? 'Update Image' : 'Upload Image' }}
                </button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
