@extends('layouts.admin')

@section('title', 'Pages')

@section('content')

<div class="page-header-bar">
    <h1>Pages</h1>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Create Page
    </a>
</div>

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-body p-0">
        @if($pages->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <p>No pages match your filters. <a href="{{ route('admin.pages.index') }}">Clear filters</a> or <a href="{{ route('admin.pages.create') }}">create one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt data-dt-searching="false">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th width="150" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td class="fw-500">{{ $page->title }}</td>
                                <td><code>/{{ $page->slug }}</code></td>
                                <td>{{ ucfirst($page->template) }}</td>
                                <td>
                                    <span class="badge {{ $page->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $page->is_active ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>{{ $page->updated_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                                           class="btn btn-sm btn-icon btn-outline-secondary"
                                           data-bs-toggle="tooltip" title="View Page"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.pages.edit', $page) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                        @if($page->isDeletionProtected())
                                            <span class="btn btn-sm btn-icon btn-outline-secondary disabled opacity-50"
                                                  data-bs-toggle="tooltip" title="This page cannot be deleted">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @else
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline"
                                              data-delete-confirm="This page will be removed from the live website.">
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
