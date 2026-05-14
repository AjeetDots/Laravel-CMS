@extends('layouts.admin')
@section('title', 'Blog Categories')
@section('content')

<div class="page-header-bar">
    <h1>Blog Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Category
    </a>
</div>

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-body p-0">
        @if($categories->isEmpty())
            <div class="text-center text-muted py-4">No categories match your filters. <a href="{{ route('admin.categories.index') }}">Clear filters</a>.</div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt data-dt-searching="false">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Posts</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td>
                                @if($cat->parent_id)
                                    <span class="text-muted me-1" style="font-size:.8rem;">—</span>
                                @endif
                                <strong>{{ $cat->name }}</strong>
                            </td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>{{ $cat->parent?->name ?? '—' }}</td>
                            <td>{{ $cat->posts_count }}</td>
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
                                    @if(($cat->posts_count ?? 0) > 0)
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-secondary"
                                                disabled
                                                data-bs-toggle="tooltip"
                                                title="Cannot delete while {{ $cat->posts_count }} blog post(s) use this category. Reassign them first.">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                                              data-delete-confirm="The category «{{ $cat->name }}» will be removed. Child categories will be promoted one level.">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-icon btn-outline-danger"
                                                    data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
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
