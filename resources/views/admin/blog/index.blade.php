@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1>Blog Posts</h1>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Post
    </a>
</div>

@include('admin.partials.listing-toolbar', [
    'showStatus' => true,
    'categoryOptions' => $categoryOptions ?? [],
])

<div class="card">
    <div class="card-body p-0">
        @if($posts->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-newspaper fa-2x mb-2"></i>
                <p>No blog posts match your filters. <a href="{{ route('admin.blog.index') }}">Clear filters</a> or <a href="{{ route('admin.blog.create') }}">create one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" data-admin-dt>
                    <thead>
                        <tr>
                            <th width="80" data-dt-orderable="false">Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th width="88" class="text-center">Sort Order</th>
                            <th>Published</th>
                            <th width="120" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
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
                                @php $catLbl = $post->resolvedCategoryLabel(); @endphp
                                @if($catLbl !== '')
                                    <span class="badge" style="background:#fdf0e8;color:#c07850;font-weight:600;">{{ $catLbl }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="font-size:.88rem;">{{ filled(trim((string) ($post->author ?? ''))) ? $post->author : $defaultAuthorName }}</td>
                            <td>
                                @if($post->is_active)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="text-center text-muted align-middle" style="font-size:.82rem;font-variant-numeric:tabular-nums;">{{ (int) ($post->sort_order ?? 0) }}</td>
                            <td style="font-size:.82rem;">{{ $post->published_at?->format('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.blog.edit', $post) }}"
                                       class="btn btn-sm btn-icon btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="d-inline"
                                              data-delete-confirm="This blog post will be removed from the live website.">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-icon btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
