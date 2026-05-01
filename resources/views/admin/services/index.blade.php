@extends('layouts.admin')

@section('title', 'Services')

@section('content')

<div class="page-header-bar">
    <h1>Services</h1>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Service
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($services->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-concierge-bell fa-2x mb-2"></i>
                <p>No services yet. <a href="{{ route('admin.services.create') }}">Add one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Icon</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th width="130">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td class="fw-500">{{ $service->title }}</td>
                                <td><code>{{ $service->slug }}</code></td>
                                <td>@if($service->icon)<i class="{{ $service->icon }}"></i> {{ $service->icon }}@endif</td>
                                <td>{{ $service->sort_order }}</td>
                                <td>
                                    <span class="badge {{ $service->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this service?')">
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
