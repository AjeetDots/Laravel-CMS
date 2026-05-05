@extends('layouts.admin')

@section('title', isset($category->id) ? 'Edit Gallery Category' : 'Add Gallery Category')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($category->id) ? 'Edit category' : 'Add category' }}</h1>
    <a href="{{ route('admin.gallery-categories.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($category->id) ? route('admin.gallery-categories.update', $category) : route('admin.gallery-categories.store') }}"
              method="POST">
            @csrf
            @if(isset($category->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required maxlength="120"
                               value="{{ old('name', $category->name) }}"
                               placeholder="e.g. Craftsmanship">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" maxlength="160"
                               value="{{ old('slug', $category->slug) }}"
                               placeholder="Leave blank to auto-generate from name">
                        <div class="form-text">Used internally; filter buttons on the public gallery use the category name.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($category->id) ? 'Update category' : 'Create category' }}
                </button>
                <a href="{{ route('admin.gallery-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
