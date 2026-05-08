@extends('layouts.admin')

@section('title', isset($service->id) ? 'Edit Service' : 'Add Service')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($service->id) ? 'Edit Service' : 'Add Service' }}</h1>
    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($service->id) ? route('admin.services.update', $service) : route('admin.services.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($service->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="titleInput" class="form-control" value="{{ old('title', $service->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slugInput" class="form-control" value="{{ old('slug', $service->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description *</label>
                        <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description', $service->short_description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Badge Label</label>
                        <input type="text" name="badge" class="form-control" value="{{ old('badge', $service->badge) }}" placeholder="e.g. SIGNATURE">
                        <div class="form-text">Short label shown above the service title on the services listing page (e.g. SIGNATURE, STATEMENT).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Feature Highlights</label>
                        <div id="features-list">
                            @php $featureItems = old('features', $service->features ?? []); @endphp
                            @forelse($featureItems as $feat)
                                <div class="input-group mb-2 feature-row">
                                    <input type="text" name="features[]" class="form-control" value="{{ $feat }}" placeholder="e.g. Marble-like luxury finish">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature">×</button>
                                </div>
                            @empty
                                <div class="input-group mb-2 feature-row">
                                    <input type="text" name="features[]" class="form-control" placeholder="e.g. Marble-like luxury finish">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature">×</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="btn-add-feature" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Feature
                        </button>
                        <div class="form-text mt-1">Shown as checkmark bullet points on the services listing page.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" id="postContent" class="form-control wysiwyg" rows="6">{{ old('description', $service->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Related finishes</label>
                        <select name="finish_ids[]" class="form-select" multiple size="8">
                            @foreach($finishes ?? [] as $fin)
                                <option value="{{ $fin->id }}"
                                    {{ collect(old('finish_ids', isset($service->id) ? $service->finishes->pluck('id') : []))->contains($fin->id) ? 'selected' : '' }}>
                                    {{ $fin->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple. Shown on the public service detail page.</div>
                    </div>

                    @include('admin.partials.seo-panel', [
                        'seo'            => $service->seoMeta ?? null,
                        'titleFieldId'   => 'titleInput',
                        'slugFieldId'    => 'slugInput',
                        'contentFieldId' => 'postContent',
                    ])
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                        @if(isset($service->id) && $service->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$service->image) }}" class="img-preview" style="height:80px;">
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="fas fa-code">
                        <div class="form-text">FontAwesome icon class e.g. <code>fas fa-code</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($service->id) ? 'Update Service' : 'Create Service' }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('btn-add-feature').addEventListener('click', function () {
    const list = document.getElementById('features-list');
    const row = document.createElement('div');
    row.className = 'input-group mb-2 feature-row';
    row.innerHTML = '<input type="text" name="features[]" class="form-control" placeholder="e.g. Marble-like luxury finish"><button type="button" class="btn btn-outline-danger btn-remove-feature">&times;</button>';
    list.appendChild(row);
    row.querySelector('input').focus();
});
document.getElementById('features-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-feature')) {
        const rows = document.querySelectorAll('.feature-row');
        if (rows.length > 1) {
            e.target.closest('.feature-row').remove();
        } else {
            e.target.closest('.feature-row').querySelector('input').value = '';
        }
    }
});
</script>
@endpush

@endsection
