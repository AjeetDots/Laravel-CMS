@extends('layouts.admin')

@section('title', 'Brands')

@section('content')

<div class="page-header-bar">
    <h1>Brands / Partners</h1>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Brand
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($brands->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-star fa-2x mb-2"></i>
                <p>No brands yet. <a href="{{ route('admin.brands.create') }}">Add one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th width="80">Logo</th>
                            <th>Name</th>
                            <th>Website</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th width="130">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td>
                                    @if(Storage::disk('public')->exists($brand->logo))
                                    <img src="{{ asset('storage/'.$brand->logo) }}"
                                         alt=""
                                         class="img-preview" style="height:40px; width:80px; object-fit:contain;">
                                    @else
                                    <span class="d-inline-block bg-light border rounded" style="height:40px;width:80px;" title="No logo"></span>
                                    @endif
                                </td>
                                <td class="fw-500">{{ $brand->name }}</td>
                                <td>
                                    @if($brand->website)
                                        <a href="{{ $brand->website }}" target="_blank" style="font-size:.85rem;">{{ $brand->website }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $brand->sort_order }}</td>
                                <td>
                                    <span class="badge {{ $brand->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.brands.edit', $brand) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this brand?')">
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
