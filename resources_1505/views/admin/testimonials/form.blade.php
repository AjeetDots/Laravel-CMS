@extends('layouts.admin')

@section('title', isset($testimonial->id) ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($testimonial->id) ? 'Edit Testimonial' : 'Add Testimonial' }}</h1>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($testimonial->id) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($testimonial->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Client Name *</label>
                            <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $testimonial->client_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Position</label>
                            <input type="text" name="client_position" class="form-control" value="{{ old('client_position', $testimonial->client_position) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" name="client_company" class="form-control" value="{{ old('client_company', $testimonial->client_company) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select">
                                @for($i=5;$i>=1;$i--)
                                    <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control wysiwyg" rows="5" required>{{ old('message', $testimonial->message) }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Client Photo *</label>
                        <input type="file" name="client_image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
                        <div class="form-text">
                            Required for home page testimonial display.
                        </div>
                        @if(isset($testimonial->id) && $testimonial->client_image)
                            <div class="mt-2">
                                <img src="{{ $testimonial->client_image_url }}" class="img-preview" style="height:60px; width:60px; border-radius:50%; object-fit:cover;">
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $testimonial->exists ? $testimonial->sort_order : ($defaultSortOrder ?? 0)) }}" min="0">
                        <div class="form-text">Must be unique among all testimonials.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($testimonial->id) ? 'Update' : 'Create' }} Testimonial
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
