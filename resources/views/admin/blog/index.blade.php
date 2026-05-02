@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1>Blog Posts</h1>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Post
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="80">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        @if($post->image)
                            <img src="{{ asset('storage/'.$post->image) }}" style="width:60px;height:44px;object-fit:cover;border-radius:4px;">
                        @else
                            <div style="width:60px;height:44px;background:#f1f5f9;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.9rem;">{{ $post->title }}</div>
                        <div style="font-size:.78rem;color:#64748b;">{{ $post->slug }}</div>
                    </td>
                    <td>
                        @if($post->category)
                            <span class="badge" style="background:#fdf0e8;color:#c07850;font-weight:600;">{{ $post->category }}</span>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td style="font-size:.88rem;">{{ $post->author ?? '—' }}</td>
                    <td>
                        @if($post->is_active)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem;">{{ $post->published_at?->format('d M Y') ?? '—' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.blog.edit', $post) }}"
                               class="btn btn-sm btn-icon btn-outline-primary"
                               data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No blog posts yet. <a href="{{ route('admin.blog.create') }}">Create one</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($posts->hasPages())
    <div class="mt-3">{{ $posts->links() }}</div>
@endif

@endsection
