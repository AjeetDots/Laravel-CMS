@php
    $hasImage = ! empty($data['image']);
@endphp

@if($hasImage && $data['image_position'] == 'left')
    <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <div class="media-frame about-feature-frame cms-section-media__frame">
            <img
                src="{{ asset('storage/'.$data['image']) }}"
                alt="{{ strip_tags($data['title'] ?? 'Section image') }}"
                class="feature-img img-fluid"
                loading="lazy"
                decoding="async"
            >
        </div>
    </div>
@endif

<div class="{{ $hasImage ? 'col-12 col-lg-6' : 'col-12' }} cms-section-media__body pt-1 pt-lg-3">
    @if(trim((string) ($data['title'] ?? '')) !== '')
        <span class="story-label">{{ $data['title'] }}</span>
    @endif

    <div class="cms-section-media__prose">
        {!! $data['content'] !!}
    </div>

    @php
        $cmsMediaButtons = collect($data['buttons'] ?? [])->filter(function ($button) {
            return trim((string) ($button['text'] ?? '')) !== '';
        });
    @endphp
    @if($cmsMediaButtons->isNotEmpty())
        <div class="cms-section-media__actions d-flex gap-3 mt-4 flex-wrap">
            @foreach($cmsMediaButtons as $button)
                <a href="{{ \App\Support\CmsOutboundHref::resolve(isset($button['link']) && trim((string) $button['link']) !== '' ? (string) $button['link'] : null, 'contact') }}" class="btn btn-dark btn-cms-section">{{ $button['text'] }}</a>
            @endforeach
        </div>
    @endif
</div>

@if($hasImage && $data['image_position'] == 'right')
    <div class="col-12 col-lg-6 mt-4 mt-lg-0">
        <div class="media-frame about-feature-frame cms-section-media__frame">
            <img
                src="{{ asset('storage/'.$data['image']) }}"
                alt="{{ strip_tags($data['title'] ?? 'Section image') }}"
                class="feature-img img-fluid"
                loading="lazy"
                decoding="async"
            >
        </div>
    </div>
@endif
