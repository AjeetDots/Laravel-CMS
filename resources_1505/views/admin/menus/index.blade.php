@extends('layouts.admin')

@section('title', 'Menus')

@section('content')

<div class="page-header-bar">
    <h1>Navigation Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Menu Item
    </a>
</div>

<p class="text-muted small mb-4">Footer links at the bottom of the site are managed separately: <a href="{{ route('admin.footer-navigation.edit') }}">Footer navigation</a> (two columns).</p>

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-body p-0">
        @if($menus->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bars fa-2x mb-2"></i>
                <p>No menu items match your filters. <a href="{{ route('admin.menus.index') }}">Clear filters</a> or <a href="{{ route('admin.menus.create') }}">add one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt data-dt-ordering="false">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>URL</th>
                            <th>Target</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th width="130" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td class="fw-600">{{ $menu->label }}</td>
                                <td><code>{{ $menu->url }}</code></td>
                                <td>{{ $menu->target }}</td>
                                <td>{{ $menu->sort_order }}</td>
                                <td>
                                    <span class="badge {{ $menu->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.menus.edit', $menu) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                                              data-delete-confirm="This menu item and its nested links will be removed from the navigation.">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-icon btn-outline-danger"
                                                    data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @foreach($menu->children as $child)
                                <tr style="background:#f8fafc;">
                                    <td class="ps-4" style="color:#64748b;">
                                        <i class="fas fa-level-down-alt fa-xs me-2"></i>{{ $child->label }}
                                    </td>
                                    <td><code>{{ $child->url }}</code></td>
                                    <td>{{ $child->target }}</td>
                                    <td>{{ $child->sort_order }}</td>
                                    <td>
                                        <span class="badge {{ $child->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="{{ route('admin.menus.edit', $child) }}"
                                               class="btn btn-sm btn-icon btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                            <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" class="d-inline"
                                                  data-delete-confirm="This menu link will be removed from the navigation.">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                                        data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
