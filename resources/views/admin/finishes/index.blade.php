@extends('layouts.admin')
@section('title', 'Finishes')
@section('content')

<div class="page-header-bar">
    <h1>Finishes</h1>
    <a href="{{ route('admin.finishes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Finish
    </a>
</div>

@include('admin.partials.module-visibility-toggle', ['module' => 'finishes'])

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Finishes ({{ $finishes->count() }} total)</span>
    </div>
    <div class="card-body p-0">
        @if($finishes->isEmpty())
            <div class="text-center py-5 text-muted">
                No finishes match your filters. <a href="{{ route('admin.finishes.index') }}">Clear filters</a> or <a href="{{ route('admin.finishes.create') }}">add one</a>.
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0" data-admin-dt>
                <thead>
                    <tr>
                        <th width="60" data-dt-orderable="false">Image</th>
                        <th>Title</th>
                        <th>Tags</th>
                        <th width="80" class="text-center">Gallery</th>
                        <th width="80" class="text-center">Order</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="110" class="text-center" data-dt-orderable="false">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($finishes as $finish)
                <tr>
                    <td>
                        @if($finish->thumbnail_url)
                            <img src="{{ $finish->thumbnail_url }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:50px;height:50px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $finish->title }}</div>
                        <div class="text-muted" style="font-size:.8rem;">{{ $finish->slug }}</div>
                    </td>
                    <td>
                        @foreach($finish->tags ?? [] as $tag)
                            <span class="badge bg-light text-dark border me-1">{{ $tag }}</span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <span class="text-muted">{{ count($finish->gallery ?? []) }}</span>
                    </td>
                    <td class="text-center">{{ $finish->sort_order }}</td>
                    <td class="text-center">
                        @if($finish->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="action-btns">
                            <a href="{{ route('admin.finishes.edit', $finish) }}"
                               class="btn btn-sm btn-icon btn-outline-primary"
                               data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.finishes.destroy', $finish) }}" method="POST"
                                  class="d-inline"
                                  data-delete-confirm="This finish will be removed from the live website.">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
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
