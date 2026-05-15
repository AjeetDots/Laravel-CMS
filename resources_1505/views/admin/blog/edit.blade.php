@extends('layouts.admin')
@section('title', 'Edit Blog Post')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1>Edit: {{ Str::limit($blog->title, 40) }}</h1>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

{{-- ── Main update form ──────────────────────────────────────────────────── --}}
<form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data" id="updateForm">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">Post Content</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" id="titleInput" class="form-control" value="{{ old('title', $blog->title) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" id="slugInput" class="form-control" value="{{ old('slug', $blog->slug) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Content</label>
                    <textarea name="content" id="postContent" class="form-control wysiwyg" rows="14">{{ old('content', $blog->content) }}</textarea>
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
                               {{ old('is_active', $blog->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Published</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Publish Date</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                           value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="categoryId">Category</label>
                    <select name="category_id" id="categoryId" class="form-select">
                        <option value="">— Uncategorised —</option>
                        @foreach($categories as $cid => $cname)
                            <option value="{{ $cid }}"
                                {{ old('category_id', $blog->category_id) == $cid ? 'selected' : '' }}>
                                {{ $cname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', $blog->author) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $blog->sort_order) }}" min="0">
                    <div class="form-text">Must be unique among posts in the same category (including “no category”).</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Featured Image</div>
            <div class="card-body">
                @if($blog->image)
                    <img src="{{ asset('storage/'.$blog->image) }}" alt="Featured" style="width:100%;border-radius:6px;margin-bottom:12px;">
                @endif
                <input type="file" name="image" class="form-control" id="imageInput"
                       accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                <div class="form-text">Leave empty to keep current image.</div>
                <div id="imagePreview" class="mt-3" style="display:none;">
                    <img id="previewImg" src="" alt="Preview" style="width:100%;border-radius:6px;">
                </div>
            </div>
        </div>

        {{-- Action buttons — delete uses a standalone form outside the update form --}}
        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Update Post
            </button>
            <button type="button" class="btn btn-outline-danger"
                    onclick="document.getElementById('deleteForm').submit()">
                <i class="fas fa-trash me-1"></i>Delete Post
            </button>
        </div>

        @include('admin.partials.seo-panel', [
            'seo'            => $blog->seoMeta,
            'titleFieldId'   => 'titleInput',
            'slugFieldId'    => 'slugInput',
            'contentFieldId' => 'postContent',
        ])
    </div>
</div>
</form>

{{-- ── Standalone delete form — kept outside the update form to avoid _method collision ── --}}
<form id="deleteForm" action="{{ route('admin.blog.destroy', $blog) }}" method="POST" style="display:none;"
      data-delete-confirm="This blog post will be removed from the live website.">
    @csrf @method('DELETE')
</form>

@endsection
@section('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function() {
    if (this.files[0]) {
        document.getElementById('previewImg').src = URL.createObjectURL(this.files[0]);
        document.getElementById('imagePreview').style.display = 'block';
    }
});
</script>
@endsection
