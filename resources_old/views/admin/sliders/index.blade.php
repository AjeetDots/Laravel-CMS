@extends('layouts.admin')

@section('title', 'Sliders')

@section('content')

<div class="page-header-bar">
    <h1>Sliders</h1>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Slider
    </a>
</div>

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-body p-0">
        @if($sliders->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-images fa-2x mb-2"></i>
                <p>No sliders match your filters. <a href="{{ route('admin.sliders.index') }}">Clear filters</a> or <a href="{{ route('admin.sliders.create') }}">add one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt data-dt-searching="false">
                    <thead>
                        <tr>
                            <th width="80" data-dt-orderable="false">Image</th>
                            <th>Title</th>
                            <th>Panel</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th width="130" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>
                                    @if(Storage::disk('public')->exists($slider->image))
                                    <img src="{{ asset('storage/'.$slider->image) }}"
                                         alt=""
                                         class="img-preview" style="height:40px; width:60px; object-fit:cover;">
                                    @else
                                    <span class="d-inline-block bg-light border rounded" style="height:40px;width:60px;" title="No image"></span>
                                    @endif
                                </td>
                                <td class="fw-500">{{ $slider->title }}</td>
                                <td>
                                    @php $panelColors = ['main'=>'primary','right_top'=>'success','right_bottom'=>'warning']; @endphp
                                    <span class="badge bg-{{ $panelColors[$slider->panel ?? 'main'] ?? 'secondary' }} bg-opacity-10 text-{{ $panelColors[$slider->panel ?? 'main'] ?? 'secondary' }} border border-{{ $panelColors[$slider->panel ?? 'main'] ?? 'secondary' }} border-opacity-25" style="font-size:.72rem;">
                                        {{ ['main'=>'Center Main','right_top'=>'Right Top','right_bottom'=>'Right Bottom'][$slider->panel ?? 'main'] ?? $slider->panel }}
                                    </span>
                                </td>
                                <td>{{ $slider->sort_order }}</td>
                                <td>
                                    <span class="badge {{ $slider->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.sliders.edit', $slider) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline"
                                              data-delete-confirm="This slide will be removed from the live website.">
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
