@php
    $activeTestimonialCount = \App\Models\Testimonial::query()
        ->where('is_active', true)
        ->whereNotNull('message')
        ->where('message', '!=', '')
        ->count();
    $leftImageRequired = $activeTestimonialCount > 0 && empty($testimonialsSection['left_image']);
@endphp
<div class="tab-pane fade @if($activeHomeSection === 'testimonials') show active @endif" id="testimonials-pane" role="tabpanel" aria-labelledby="testimonials-tab" tabindex="0">
    <div class="mb-3">
        <p class="form-text mb-2">
            The home page testimonials block appears only when you have at least one active entry in
            <a href="{{ route('admin.testimonials.index') }}">Testimonials</a> with review text.
            Left panel image and headings are configured here; quotes and client details come from those entries.
        </p>
        @if($activeTestimonialCount === 0)
            <div class="alert alert-info py-2 mb-0 small">
                No active testimonials yet — this section is hidden on the home page until you add one.
            </div>
        @else
            <div class="alert alert-success py-2 mb-0 small">
                {{ $activeTestimonialCount }} active {{ \Illuminate\Support\Str::plural('testimonial', $activeTestimonialCount) }} will show on the home page.
            </div>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label" for="home_testimonials_left_eyebrow">Left panel label</label>
            <input type="text" name="home_testimonials_left_eyebrow" id="home_testimonials_left_eyebrow" class="form-control"
                   data-sync-home-section-tab="testimonials"
                   value="{{ old('home_testimonials_left_eyebrow', $testimonialsSection['left_eyebrow'] ?? '') }}"
                   placeholder="e.g. Testimonial">
        </div>
        <div class="col-md-8">
            <label class="form-label" for="home_testimonials_left_headline">Left panel headline</label>
            <input type="text" name="home_testimonials_left_headline" id="home_testimonials_left_headline" class="form-control"
                   value="{{ old('home_testimonials_left_headline', $testimonialsSection['left_headline'] ?? '') }}"
                   placeholder="e.g. Luxury Experiences Shared">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="home_testimonials_right_eyebrow">Right panel label</label>
            <input type="text" name="home_testimonials_right_eyebrow" id="home_testimonials_right_eyebrow" class="form-control"
                   value="{{ old('home_testimonials_right_eyebrow', $testimonialsSection['right_eyebrow'] ?? '') }}"
                   placeholder="e.g. Customer Reviews">
        </div>
        <div class="col-md-8">
            <label class="form-label">Left panel image
                @if($activeTestimonialCount > 0)
                    <span class="text-danger">*</span>
                @endif
            </label>
            @if(!empty($testimonialsSection['left_image']))
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $testimonialsSection['left_image']) }}" alt="Testimonials left panel" class="img-preview">
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remove_home_testimonials_left_image" value="1" id="remove_home_testimonials_left_image">
                    <label class="form-check-label text-danger" for="remove_home_testimonials_left_image">Remove current image</label>
                </div>
            @endif
            <input type="file"
                   name="home_testimonials_left_image"
                   id="home_testimonials_left_image"
                   class="form-control @error('home_testimonials_left_image') is-invalid @enderror"
                   accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                   @if($leftImageRequired) required @endif>
            @error('home_testimonials_left_image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="form-text">
                @if($activeTestimonialCount > 0)
                    Required while active testimonials exist. Large static photo on the left (does not change with the review slider).
                @else
                    Optional until you publish at least one active testimonial.
                @endif
            </div>
        </div>
    </div>
</div>
