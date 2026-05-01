@extends('layouts.admin')
@section('title', 'Edit Blog Post')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1>Edit: {{ Str::limit($blog->title, 40) }}</h1>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">Post Content</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $blog->slug) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Content</label>
                    <textarea name="content" class="form-control" rows="14">{{ old('content', $blog->content) }}</textarea>
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
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $blog->category) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', $blog->author) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $blog->sort_order) }}">
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">Featured Image</div>
            <div class="card-body">
                @if($blog->image)
                    <img src="{{ asset('storage/'.$blog->image) }}" style="width:100%;border-radius:6px;margin-bottom:12px;">
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                <div class="form-text">Leave empty to keep current image.</div>
                <div id="imagePreview" class="mt-3" style="display:none;">
                    <img id="previewImg" src="" style="width:100%;border-radius:6px;">
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Update Post
            </button>
            <form action="{{ route('admin.blog.destroy', $blog) }}" method="POST"
                  onsubmit="return confirm('Delete this post permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="fas fa-trash me-1"></i>Delete Post
                </button>
            </form>
        </div>
    </div>
</div>
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
