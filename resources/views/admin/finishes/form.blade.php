@extends('layouts.admin')
@section('title', isset($finish->id) ? 'Edit Finish' : 'Add Finish')
@section('content')

<div class="page-header-bar">
    <h1>{{ isset($finish->id) ? 'Edit Finish' : 'Add Finish' }}</h1>
    <a href="{{ route('admin.finishes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($finish->id) ? route('admin.finishes.update', $finish) : route('admin.finishes.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($finish->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="titleInput" class="form-control"
                               value="{{ old('title', $finish->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slugInput" class="form-control"
                               value="{{ old('slug', $finish->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control wysiwyg" rows="6"
                                  id="postContent">{{ old('description', $finish->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Use Cases</label>
                        <textarea name="use_cases" class="form-control wysiwyg" rows="4"
                                  placeholder="e.g. Where this finish is typically used…">{{ old('use_cases', $finish->use_cases) }}</textarea>
                        <div class="form-text">Describe typical applications, ideal rooms or surfaces.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags <small class="text-muted">(comma-separated)</small></label>
                        <input type="text" name="tags_raw" class="form-control"
                               value="{{ old('tags_raw', implode(', ', $finish->tags ?? [])) }}"
                               placeholder="e.g. Marble, Luxury, Interior">
                    </div>

                    @include('admin.partials.seo-panel', [
                        'seo'            => $finish->seoMeta ?? null,
                        'titleFieldId'   => 'titleInput',
                        'slugFieldId'    => 'slugInput',
                        'contentFieldId' => 'postContent',
                    ])
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                        @if(isset($finish->id) && $finish->cover_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$finish->cover_image) }}" class="img-preview" style="height:100px;width:100%;object-fit:cover;border-radius:8px;">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gallery Images <small class="text-muted">(multiple)</small></label>
                        <input type="file" name="gallery_images[]" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml" multiple>
                        @if(isset($finish->id) && !empty($finish->gallery))
                            <div class="mt-2">
                                <div class="d-flex flex-wrap gap-1">
                                @foreach($finish->gallery as $img)
                                    <img src="{{ asset('storage/'.$img) }}" style="width:58px;height:58px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                                @endforeach
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="clear_gallery" value="1" id="clearGallery">
                                    <label class="form-check-label text-danger small" for="clearGallery">Clear all gallery images before uploading new ones</label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $finish->exists ? $finish->sort_order : ($defaultSortOrder ?? 1)) }}" min="1">
                        <div class="form-text">Each number may only appear once across all finishes.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $finish->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                    <div class="d-flex gap-2 justify-content-end flex-wrap mt-4 pt-3 border-top">
                        <a href="{{ route('admin.finishes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($finish->id) ? 'Update Finish' : 'Create Finish' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
