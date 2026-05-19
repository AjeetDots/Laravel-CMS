@extends('layouts.frontend')
@section('title', 'Gallery')
@section('body_class', 'nav-solid page-gallery')
@section('content')

<section class="gallery-intro">
    <div class="container">
        <span class="gallery-intro__eyebrow">{{ $galleryPage['intro_eyebrow'] }}</span>
        <h1 class="gallery-intro__title">{{ $galleryPage['intro_title'] }}</h1>
    </div>
</section>

<section class="gallery-showcase">
    <div class="container">
        {{-- Category filters --}}
        @if($categories->count())
        <div class="gallery-showcase__filterbar" id="filterBtns">
            <button type="button" class="gallery-showcase__filterbtn active" data-cat="all">{{ $galleryPage['filter_all_label'] }}</button>
            @foreach($categories as $filterCat)
            <button type="button" class="gallery-showcase__filterbtn" data-cat="{{ $filterCat->name }}">{{ $filterCat->name }}</button>
            @endforeach
        </div>
        @endif

        <div class="gallery-showcase__rule" aria-hidden="true"></div>

        <div class="gallery-showcase__grid" id="galleryGrid">
            @forelse($gallery as $item)
            @php
                $position = $loop->index + 1;
                $layoutClass = match ($position) {
                    1 => 'gallery-showcase__item gallery-showcase__item--hero',
                    2 => 'gallery-showcase__item gallery-showcase__item--feature',
                    default => 'gallery-showcase__item gallery-showcase__item--standard',
                };
                $categoryLabel = $item->galleryCategory?->name
                    ? \Illuminate\Support\Str::upper($item->galleryCategory->name)
                    : \Illuminate\Support\Str::upper(trim((string) ($galleryPage['grid_category_fallback'] ?? '')));
            @endphp
            <article class="{{ $layoutClass }} gal-col" data-cat="{{ $item->galleryCategory?->name ?? '' }}">
                <div class="gallery-work-card" role="button" tabindex="0"
                    data-bs-toggle="modal" data-bs-target="#galleryLightbox"
                    data-img="{{ $item->image_url }}"
                    data-title="{{ $item->title ?? '' }}">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title ?? '' }}"
                        class="img-fallback">
                    <div class="gallery-work-card__overlay">
                        <div class="gallery-work-card__meta">
                            <span>{{ $categoryLabel }}</span>
                        </div>
                        @if($item->title)
                            <h3>{{ $item->title }}</h3>
                        @endif
                        @if(filled($item->section_content))
                            <p class="commission-title commission-text mt-3 text-white">{!! nl2br(e($item->section_content)) !!}</p>
                        @endif
                        <span class="gallery-work-card__link"><i class="fas fa-arrow-up-right"></i></span>
                    </div>
                </div>
            </article>
            @empty
            <div class="gallery-showcase__empty text-center py-5">
                <i class="fas fa-images fa-3x mb-3" aria-hidden="true"></i>
                <p class="mb-3">{{ $galleryPage['empty_message'] }}</p>
                @php
                    $galleryEmptyBtnText = trim((string) ($galleryPage['empty_btn_text'] ?? ''));
                    $galleryEmptyBtnHref = trim((string) ($galleryPage['empty_btn_href'] ?? ''));
                    if ($galleryEmptyBtnText === '') {
                        $galleryEmptyBtnText = 'Get in touch';
                    }
                    if ($galleryEmptyBtnHref === '') {
                        $galleryEmptyBtnHref = route('contact');
                    }
                @endphp
                <a href="{{ $galleryEmptyBtnHref }}" class="hero-btn hero-btn--gold home-atelier-btn">
                    {{ $galleryEmptyBtnText }}
                    <i class="fa-solid fa-arrow-up-right" style="font-size:.72rem;" aria-hidden="true"></i>
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="gallery-bottom-cta">
    <div class="container text-center">
        <h2>{{ $galleryPage['bottom_heading'] }}</h2>
        @if(!empty(trim($galleryPage['bottom_btn_text'] ?? '')) && !empty(trim($galleryPage['bottom_btn_href'] ?? '')))
        <a href="{{ $galleryPage['bottom_btn_href'] }}" class="hero-btn hero-btn--gold home-atelier-btn">
            {{ $galleryPage['bottom_btn_text'] }}
            <i class="fa-solid fa-arrow-up-right" style="font-size:.72rem;" aria-hidden="true"></i>
        </a>
        @endif
    </div>
</section>

{{-- Lightbox --}}
<div class="modal fade" id="galleryLightbox" tabindex="-1" aria-labelledby="galleryLightboxTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 py-2 px-3">
                <h2 class="modal-title fs-6 text-white-50 mb-0" id="galleryLightboxTitle"></h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="" alt="" id="galleryLightboxImg" class="w-100" style="max-height:85vh;object-fit:contain;background:#111;">
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
document.getElementById('galleryLightbox')?.addEventListener('show.bs.modal', function (event) {
    var trigger = event.relatedTarget;
    if (!trigger) return;
    var img = trigger.getAttribute('data-img');
    var title = trigger.getAttribute('data-title') || '';
    document.getElementById('galleryLightboxImg').src = img || '';
    document.getElementById('galleryLightboxImg').alt = title;
    document.getElementById('galleryLightboxTitle').textContent = title;
});

document.querySelectorAll('img.img-fallback').forEach(function(img) {
    img.addEventListener('error', function() {
        var fb = this.getAttribute('data-fallback');
        if (fb) { this.src = fb; }
    });
});

document.querySelectorAll('.gallery-work-card').forEach(function(card) {
    card.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            card.click();
        }
    });
});

document.querySelectorAll('.gallery-showcase__filterbtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const cat = this.dataset.cat;
        document.querySelectorAll('.gallery-showcase__filterbtn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.gal-col').forEach(col => {
            col.style.display = (cat === 'all' || col.dataset.cat === cat) ? '' : 'none';
        });
    });
});
</script>
@endsection
