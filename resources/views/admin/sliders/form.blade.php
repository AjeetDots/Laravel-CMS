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
                        <label class="form-label">Title * <span class="text-muted fw-normal">(line 1 of the hero headline)</span></label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $slider->title) }}" required
                               placeholder="e.g. Quality">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title line 2</label>
                        <input type="text" name="title_line_2" class="form-control"
                               value="{{ old('title_line_2', $slider->title_line_2) }}"
                               placeholder="Optional — e.g. Solutions,">
                        <div class="form-text">
                            Add line 2–4 only if you want fixed line breaks in the hero. Leave all three empty to show <strong>Title</strong> as one block (wraps naturally).
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title line 3</label>
                            <input type="text" name="title_line_3" class="form-control"
                                   value="{{ old('title_line_3', $slider->title_line_3) }}"
                                   placeholder="e.g. On Time &amp;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title line 4</label>
                            <input type="text" name="title_line_4" class="form-control"
                                   value="{{ old('title_line_4', $slider->title_line_4) }}"
                                   placeholder="e.g. On Budget">
                        </div>
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
                                  placeholder="Supporting line under the main headline (optional).">{{ old('lead_text', $slider->lead_text) }}</textarea>
                        <div class="form-text">
                            Shown below the hero title on the home page. Leave empty for no paragraph (blank).
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="button_text" class="form-control"
                                   value="{{ old('button_text', $slider->button_text) }}"
                                   placeholder="e.g. Explore Now">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Link</label>
                            <input type="text" name="button_link" class="form-control"
                                   value="{{ old('button_link', $slider->button_link) }}"
                                   placeholder="/services">
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
                               value="{{ old('sort_order', $slider->sort_order ?? 0) }}" min="0">
                        <div class="form-text">Lower = appears first in the cycling sequence.</div>
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
