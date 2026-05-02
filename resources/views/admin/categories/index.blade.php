@extends('layouts.admin')
@section('title', 'Blog Categories')
@section('content')

<div class="page-header-bar">
    <h1>Blog Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th>Posts</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        @if($cat->parent_id)
                            <span class="text-muted me-1" style="font-size:.8rem;">—</span>
                        @endif
                        <strong>{{ $cat->name }}</strong>
                    </td>
                    <td><code>{{ $cat->slug }}</code></td>
                    <td>{{ $cat->parent?->name ?? '—' }}</td>
                    <td>{{ $cat->posts_count ?? $cat->posts()->count() }}</td>
                    <td>{{ $cat->sort_order }}</td>
                    <td>
                        <span class="badge {{ $cat->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="action-btns justify-content-end">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="btn btn-sm btn-icon btn-outline-primary"
                               data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete «{{ addslashes($cat->name) }}»? Child categories will become top-level.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
