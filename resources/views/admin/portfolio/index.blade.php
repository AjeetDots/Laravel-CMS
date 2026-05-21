@extends('layouts.admin')
@section('title', 'Portfolio')
@section('content')

<div class="page-header-bar">
    <h1>Portfolio</h1>
    <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Project
    </a>
</div>

@include('admin.partials.module-visibility-toggle', ['module' => 'portfolio'])

@include('admin.partials.listing-toolbar', ['showStatus' => true, 'showProjectType' => true])

<div class="card">
    <div class="card-header">Projects ({{ $portfolios->count() }} total)</div>
    <div class="card-body p-0">
        @if($portfolios->isEmpty())
            <div class="text-center py-5 text-muted">
                No projects match your filters. <a href="{{ route('admin.portfolio.index') }}">Clear filters</a> or <a href="{{ route('admin.portfolio.create') }}">add one</a>.
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0" data-admin-dt data-dt-searching="false">
                <thead>
                    <tr>
                        <th width="60" data-dt-orderable="false">Image</th>
                        <th>Title</th>
                        <th width="120">Type</th>
                        <th>Tags</th>
                        <th width="80" class="text-center">Gallery</th>
                        <th width="80" class="text-center">Order</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="110" class="text-center" data-dt-orderable="false">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($portfolios as $portfolio)
                <tr>
                    <td>
                        @if($portfolio->cover_image)
                            <img src="{{ asset('storage/'.$portfolio->cover_image) }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:50px;height:50px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $portfolio->title }}</div>
                        <div class="text-muted" style="font-size:.8rem;">{{ $portfolio->slug }}</div>
                    </td>
                    <td>
                        @if($portfolio->project_type === 'real')
                            <span class="badge" style="background:#dcfce7;color:#16a34a;">Real Project</span>
                        @else
                            <span class="badge" style="background:#dbeafe;color:#1d4ed8;">Reference</span>
                        @endif
                    </td>
                    <td>
                        @foreach($portfolio->tags ?? [] as $tag)
                            <span class="badge bg-light text-dark border me-1">{{ $tag }}</span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <span class="text-muted">{{ count($portfolio->gallery ?? []) }}</span>
                    </td>
                    <td class="text-center">{{ $portfolio->sort_order }}</td>
                    <td class="text-center">
                        @if($portfolio->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="action-btns">
                            <a href="{{ route('admin.portfolio.edit', $portfolio) }}"
                               class="btn btn-sm btn-icon btn-outline-primary"
                               data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.portfolio.destroy', $portfolio) }}" method="POST"
                                  class="d-inline"
                                  data-delete-confirm="This project will be removed from the live website.">
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
