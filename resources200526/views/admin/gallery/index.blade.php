@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')

<div class="page-header-bar">
    <h1>Gallery</h1>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.gallery-categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-folder-tree me-2"></i>Categories
        </a>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
            <i class="fas fa-upload me-2"></i>Upload Image
        </a>
    </div>
</div>

@include('admin.partials.listing-toolbar', [
    'showStatus' => true,
    'galleryCategoryOptions' => $galleryCategoryOptions ?? [],
])

<div class="card">
    <div class="card-body p-0">
        @if($items->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-photo-video fa-2x mb-2"></i>
                <p>No items match your filters. <a href="{{ route('admin.gallery.index') }}">Clear filters</a> or <a href="{{ route('admin.gallery.create') }}">upload one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt>
                    <thead>
                        <tr>
                            <th width="80" data-dt-orderable="false">Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th width="130" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    @if(Storage::disk('public')->exists($item->image))
                                    <img src="{{ asset('storage/'.$item->image) }}"
                                         alt=""
                                         class="img-preview" style="height:45px; width:65px; object-fit:cover;">
                                    @else
                                    <span class="d-inline-block bg-light border rounded" style="height:45px;width:65px;" title="No image"></span>
                                    @endif
                                </td>
                                <td class="fw-500">{{ $item->title ?? '' }}</td>
                                <td>{{ $item->galleryCategory?->name ?? '' }}</td>
                                <td>{{ $item->sort_order }}</td>
                                <td>
                                    <span class="badge {{ $item->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.gallery.edit', $item) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="d-inline"
                                              data-delete-confirm="This gallery image will be removed from the live website.">
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
