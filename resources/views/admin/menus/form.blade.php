@extends('layouts.admin')

@section('title', isset($menu->id) ? 'Edit Menu Item' : 'Add Menu Item')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($menu->id) ? 'Edit Menu Item' : 'Add Menu Item' }}</h1>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($menu->id) ? route('admin.menus.update', $menu) : route('admin.menus.store') }}"
              method="POST">
            @csrf
            @if(isset($menu->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name (identifier) *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required placeholder="e.g. home">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label (display text) *</label>
                        <input type="text" name="label" class="form-control" value="{{ old('label', $menu->label) }}" required placeholder="e.g. Home">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" name="url" class="form-control" value="{{ old('url', $menu->url) }}" placeholder="/ or /services or https://...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Parent Menu</label>
                        <select name="parent_id" class="form-select">
                            <option value="">— Top Level —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <select name="target" class="form-select">
                            <option value="_self" {{ old('target', $menu->target ?? '_self') == '_self' ? 'selected' : '' }}>Same Window (_self)</option>
                            <option value="_blank" {{ old('target', $menu->target) == '_blank' ? 'selected' : '' }}>New Tab (_blank)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $menu->sort_order ?? 0) }}" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($menu->id) ? 'Update' : 'Create' }} Menu Item
                </button>
                <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
