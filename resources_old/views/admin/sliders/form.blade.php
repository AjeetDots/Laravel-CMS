@extends('layouts.admin')

@section('title', isset($slider->id) ? 'Edit Slider' : 'Add Slider')

@section('styles')
@endsection

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($slider->id) ? 'Edit Slider' : 'Add Slider' }}</h1>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($slider->id) ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($slider->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                {{-- LEFT: Text fields --}}
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title * <span class="text-muted fw-normal">(hero headline)</span></label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $slider->title) }}" required
                               placeholder="e.g. Quality">
                        <div class="form-text">Shown as the main headline on the slide; wraps naturally on smaller screens.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle <span class="text-muted fw-normal">(shown as eyebrow tag on slide)</span></label>
                        <input type="text" name="subtitle" class="form-control"
                               value="{{ old('subtitle', $slider->subtitle) }}"
                               placeholder="e.g. Artisan Collections 2024">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hero lead paragraph</label>
                        <textarea name="lead_text" class="form-control" rows="3"
                                  placeholder="e.g. Supporting line under the headline (optional)">{{ old('lead_text', $slider->lead_text) }}</textarea>
                        <div class="form-text">
                            Shown below the hero title on the home page. Leave empty for no paragraph (blank).
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Primary button text <span class="text-muted fw-normal">(gold)</span></label>
                            <input type="text" name="button_text" class="form-control"
                                   value="{{ old('button_text', $slider->button_text) }}"
                                   placeholder="e.g. Meet us">
                            <div class="form-text">Leave blank to hide this button on the site.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Primary button link</label>
                            <input type="text" name="button_link" class="form-control"
                                   value="{{ old('button_link', $slider->button_link) }}"
                                   placeholder="e.g. /about">
                            <div class="form-text">If the label is set but the link is blank, the button opens the Contact page.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Second button text <span class="text-muted fw-normal">(outline)</span></label>
                            <input type="text" name="button2_text" class="form-control"
                                   value="{{ old('button2_text', $slider->button2_text) }}"
                                   placeholder="e.g. Get in touch">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Second button link</label>
                            <input type="text" name="button2_link" class="form-control"
                                   value="{{ old('button2_link', $slider->button2_link) }}"
                                   placeholder="e.g. /contact">
                            <div class="form-text">The outline button is hidden when this label is empty. If the label is set but the link is blank, the button opens the Contact page.</div>
                        </div>
                    </div>

                    <input type="hidden" name="panel" value="main">
                </div>

                {{-- RIGHT: Image + meta --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="slider_image">Image {{ isset($slider->id) ? '' : '*' }}</label>
                        <input type="file" name="image" id="slider_image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                               {{ isset($slider->id) ? '' : 'required' }}
                               onchange="previewImg(this)">
                        <div class="mt-2" id="imgPreviewWrap">
                            @if(isset($slider->id) && $slider->image)
                                <img src="{{ asset('storage/'.$slider->image) }}" id="imgPreview"
                                     alt="Current slider"
                                     style="height:90px;width:100%;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                            @else
                                <img id="imgPreview" alt="Preview"
                                     style="display:none;height:90px;width:100%;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                            @endif
                        </div>
                        <p class="text-muted mt-1" style="font-size:.75rem;">
                            Recommended: 1400×800px or wider. JPG, PNG, WebP, or SVG.<br>
                            Center main images look best landscape; right thumbnails work portrait too.
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $slider->exists ? $slider->sort_order : ($defaultSortOrder ?? 0)) }}" min="0">
                        <div class="form-text">Lower = appears first in the cycling sequence. Each number must be unique within this panel (main vs right thumbnails).</div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($slider->id) ? 'Update Slider' : 'Create Slider' }}
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('imgPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
