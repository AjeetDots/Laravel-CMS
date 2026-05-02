@extends('layouts.admin')
@section('title', $category->id ? 'Edit Category' : 'Add Category')
@section('content')

<div class="page-header-bar">
    <h1>{{ $category->id ? 'Edit Category' : 'Add Category' }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-body">
        <form action="{{ $category->id ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
              method="POST">
            @csrf
            @if($category->id) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name" id="catName" class="form-control"
                       value="{{ old('name', $category->name) }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" id="catSlug" class="form-control"
                       value="{{ old('slug', $category->slug) }}" placeholder="auto-generated if empty">
            </div>

            <div class="mb-3">
                <label class="form-label">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">— None (top level) —</option>
                    @foreach($parents as $pid => $pname)
                        <option value="{{ $pid }}"
                            {{ old('parent_id', $category->parent_id) == $pid ? 'selected' : '' }}>
                            {{ $pname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                           value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                </div>
                <div class="col-sm-6 d-flex align-items-end pb-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ $category->id ? 'Update Category' : 'Create Category' }}
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
document.getElementById('catName').addEventListener('input', function () {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    const slugField = document.getElementById('catSlug');
    if (!slugField.dataset.touched) { slugField.placeholder = slug; }
});
document.getElementById('catSlug').addEventListener('input', function () {
    this.dataset.touched = '1';
});
</script>
@endsection
