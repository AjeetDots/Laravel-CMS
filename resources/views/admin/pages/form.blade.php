@extends('layouts.admin')

@section('title', isset($page->id) ? 'Edit Page' : 'Create Page')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($page->id) ? 'Edit Page' : 'Create Page' }}</h1>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<form action="{{ isset($page->id) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      method="POST">
    @csrf
    @if(isset($page->id)) @method('PUT') @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">Page Content</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="12">{{ old('content', $page->content) }}</textarea>
                        <div class="form-text">You can use HTML tags for formatting.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Template</label>
                        <select name="template" class="form-select">
                            @foreach($templates as $value => $label)
                                <option value="{{ $value }}" {{ old('template', $page->template ?? 'default') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Published</label>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">SEO Meta</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>{{ isset($page->id) ? 'Update Page' : 'Create Page' }}
        </button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@endsection
