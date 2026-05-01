@extends('layouts.admin')

@section('title', 'Pages')

@section('content')

<div class="page-header-bar">
    <h1>Pages</h1>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Create Page
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($pages->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <p>No pages yet. <a href="{{ route('admin.pages.create') }}">Create one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th width="150">Actions</th>
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
                                    <a href="{{ route('page.show', $page->slug) }}" class="btn btn-sm btn-outline-secondary me-1" target="_blank">View</a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this page?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Del</button>
                                    </form>
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
