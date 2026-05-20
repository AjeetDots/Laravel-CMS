@extends('layouts.admin')
@section('title', 'New Blog Post')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1>New Blog Post</h1>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">Post Content</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required
                           placeholder="e.g. Post title" id="titleInput">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}"
                           placeholder="e.g. auto-generated from title" id="slugInput">
                </div>
                <div class="mb-3">
                    <label class="form-label">Excerpt <small class="text-muted">(short summary, shown on listing)</small></label>
                    <textarea name="excerpt" class="form-control" rows="3" placeholder="e.g. One-line summary for listings…">{{ old('excerpt') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Content</label>
                    <textarea name="content" class="form-control wysiwyg" rows="14" id="postContent">{{ old('content') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">Publish Settings</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Published</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Publish Date</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— Uncategorised —</option>
                        @foreach($categories as $cid => $cname)
                            <option value="{{ $cid }}" {{ (string) old('category_id', request('category_id')) === (string) $cid ? 'selected' : '' }}>{{ $cname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author') }}"
                           placeholder="e.g. Author name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $defaultSortOrder ?? 1) }}" min="1">
                    <div class="form-text">Must be unique among posts in the same category (including “no category”).</div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">Featured Image</div>
            <div class="card-body">
                <input type="file" name="image" class="form-control" id="imageInput" required
                       accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                <div class="form-text">Recommended: 800×500px. Max 3MB.</div>
                <div id="imagePreview" class="mt-3" style="display:none;">
                    <img id="previewImg" src="" style="width:100%;border-radius:6px;">
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Save Post
            </button>
        </div>

        @include('admin.partials.seo-panel', [
            'seo'            => null,
            'titleFieldId'   => 'titleInput',
            'slugFieldId'    => 'slugInput',
            'contentFieldId' => 'postContent',
        ])
    </div>
</div>
</form>

@endsection
@section('scripts')
<script>
document.getElementById('titleInput').addEventListener('input', function() {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    document.getElementById('slugInput').placeholder = slug;
});
document.getElementById('imageInput').addEventListener('change', function() {
    if (this.files[0]) {
        document.getElementById('previewImg').src = URL.createObjectURL(this.files[0]);
        document.getElementById('imagePreview').style.display = 'block';
    }
});
</script>
@endsection
