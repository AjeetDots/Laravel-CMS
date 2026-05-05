@extends('layouts.admin')
@section('title', isset($portfolio->id) ? 'Edit Project' : 'Add Project')
@section('content')

<div class="page-header-bar">
    <h1>{{ isset($portfolio->id) ? 'Edit Project' : 'Add Project' }}</h1>
    <a href="{{ route('admin.portfolio.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($portfolio->id) ? route('admin.portfolio.update', $portfolio) : route('admin.portfolio.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($portfolio->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Project Title *</label>
                        <input type="text" name="title" id="titleInput" class="form-control"
                               value="{{ old('title', $portfolio->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slugInput" class="form-control"
                               value="{{ old('slug', $portfolio->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control wysiwyg" rows="6"
                                  id="postContent">{{ old('description', $portfolio->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags / Categories <small class="text-muted">(comma-separated)</small></label>
                        <input type="text" name="tags_raw" class="form-control"
                               value="{{ old('tags_raw', implode(', ', $portfolio->tags ?? [])) }}"
                               placeholder="e.g. Venetian Plaster, Residential, Feature Wall">
                        <div class="form-text">Used for filtering on the frontend portfolio page.</div>
                    </div>

                    @include('admin.partials.seo-panel', [
                        'seo'            => $portfolio->seoMeta ?? null,
                        'titleFieldId'   => 'titleInput',
                        'slugFieldId'    => 'slugInput',
                        'contentFieldId' => 'postContent',
                    ])
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Project Type *</label>
                        <select name="project_type" class="form-select" required>
                            <option value="real"      {{ old('project_type', $portfolio->project_type ?? 'real') === 'real'      ? 'selected' : '' }}>Real Project</option>
                            <option value="reference" {{ old('project_type', $portfolio->project_type ?? 'real') === 'reference' ? 'selected' : '' }}>Reference</option>
                        </select>
                        <div class="form-text">Real = completed work. Reference = inspiration/sample.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                        @if(isset($portfolio->id) && $portfolio->cover_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$portfolio->cover_image) }}" class="img-preview" style="height:100px;width:100%;object-fit:cover;border-radius:8px;">
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gallery Images <small class="text-muted">(multiple)</small></label>
                        <input type="file" name="gallery_images[]" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml" multiple>
                        @if(isset($portfolio->id) && !empty($portfolio->gallery))
                            <div class="mt-2">
                                <div class="d-flex flex-wrap gap-1">
                                @foreach($portfolio->gallery as $img)
                                    <img src="{{ asset('storage/'.$img) }}" style="width:58px;height:58px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                                @endforeach
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="clear_gallery" value="1" id="clearGallery">
                                    <label class="form-check-label text-danger small" for="clearGallery">Clear all gallery images</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $portfolio->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active (visible on frontend)</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($portfolio->id) ? 'Update Project' : 'Create Project' }}
                </button>
                <a href="{{ route('admin.portfolio.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
