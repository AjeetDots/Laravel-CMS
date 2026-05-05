@extends('layouts.admin')

@section('title', 'Gallery Categories')

@section('content')

<div class="page-header-bar">
    <h1>Gallery categories</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-photo-video me-2"></i>Gallery items
        </a>
        <a href="{{ route('admin.gallery-categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Images</th>
                    <th>Sort</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td><code class="small">{{ $cat->slug }}</code></td>
                    <td>{{ $cat->gallery_items_count }}</td>
                    <td>{{ $cat->sort_order }}</td>
                    <td class="text-end">
                        <div class="action-btns justify-content-end">
                            <a href="{{ route('admin.gallery-categories.edit', $cat) }}"
                               class="btn btn-sm btn-icon btn-outline-primary"
                               data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.gallery-categories.destroy', $cat) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete «{{ addslashes($cat->name) }}»? Items will lose this category.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No categories yet. Create one for gallery filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
