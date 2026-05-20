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
                        <div class="form-text">Must be unique among all gallery items (titles can be left blank).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section content</label>
                        <textarea name="section_content" class="form-control" rows="4" maxlength="2000"
                                  placeholder="Optional. Shown on the home page &quot;Selected work&quot; grid under the title (e.g. a short project caption).">{{ old('section_content', $item->section_content ?? '') }}</textarea>
                        <div class="form-text">Shown on the home page &quot;Selected work&quot; grid and the public gallery page overlay; leave blank if you only need the image and title.</div>
                    </div>
                    <div class="mb-3">
                        @php
                            $selectedCategoryId = old('gallery_category_id', $item->gallery_category_id ?? request('gallery_category_id'));
                        @endphp
                        <label class="form-label" for="galleryCategoryId">Category <span class="text-danger">*</span></label>
                        @if($categories->isEmpty())
                            <p class="text-muted mb-2">Add at least one category before uploading gallery images.</p>
                            <a href="{{ route('admin.gallery-categories.create') }}" class="btn btn-sm btn-outline-primary">Add gallery category</a>
                        @else
                            <select name="gallery_category_id" id="galleryCategoryId" class="form-select @error('gallery_category_id') is-invalid @enderror" required>
                                <option value="" disabled @selected($selectedCategoryId === null || $selectedCategoryId === '')>Select a category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        @selected((string) $selectedCategoryId === (string) $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gallery_category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Required. Manage labels in <a href="{{ route('admin.gallery-categories.index') }}">Gallery categories</a>.
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        @php
                            $sortOrderValue = old('sort_order');
                            if ($sortOrderValue === null) {
                                $sortOrderValue = $item->exists
                                    ? max(1, (int) $item->sort_order)
                                    : ($defaultSortOrder ?? '');
                            }
                        @endphp
                        <input type="number" name="sort_order" id="gallerySortOrder" class="form-control" value="{{ $sortOrderValue }}" min="1">
                        <div class="form-text">Must be unique within the same gallery category.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                               @if(!isset($item->id) || !$item->image) required @endif>
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
                <button type="submit" class="btn btn-primary" @disabled($categories->isEmpty())>
                    <i class="fas fa-save me-2"></i>{{ isset($item->id) ? 'Update Image' : 'Upload Image' }}
                </button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@if(!$item->exists && !empty($defaultSortOrderByCategory ?? []))
@push('scripts')
<script>
(function () {
    var categorySelect = document.getElementById('galleryCategoryId');
    var sortInput = document.getElementById('gallerySortOrder');
    var defaults = @json($defaultSortOrderByCategory);
    if (!categorySelect || !sortInput || !defaults) {
        return;
    }
    function applyDefaultSortOrder() {
        var key = categorySelect.value || '';
        if (Object.prototype.hasOwnProperty.call(defaults, key)) {
            sortInput.value = defaults[key];
        }
    }
    categorySelect.addEventListener('change', applyDefaultSortOrder);
    applyDefaultSortOrder();
})();
</script>
@endpush
@endif

@endsection
