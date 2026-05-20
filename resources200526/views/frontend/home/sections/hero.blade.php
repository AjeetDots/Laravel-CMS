@php
    $firstSlide = $sliders->first();
    $heroEyebrowDefault = 'Bespoke Ornate · Plaster Atelier';
    $heroLeadDefault = 'A London atelier crafting marble-like finishes, sculptural media walls and ornate mouldings for the world\'s most discerning interiors.';
@endphp

<section class="hero-full hero-full--premium" id="hero"
         data-hero-eyebrow-default="{{ e($heroEyebrowDefault) }}"
         data-hero-lead-default="{{ e($heroLeadDefault) }}"
         data-hero-fallback-contact-href="{{ route('contact') }}">

    @forelse($sliders as $i => $slide)
        @php
            $heroSlots = $slide->heroHeadlineDataSlots();
        @endphp
        <div class="hero-slide-item {{ $i === 0 ? 'active' : '' }}"
             style="background-image:url('{{ $slide->image_url }}');"
             data-title="{{ e($heroSlots[0]) }}"
             data-title-line-2="{{ e($heroSlots[1]) }}"
             data-title-line-3="{{ e($heroSlots[2]) }}"
             data-title-line-4="{{ e($heroSlots[3]) }}"
             data-subtitle="{{ e($slide->subtitle) }}"
             data-lead="{{ e($slide->lead_text ?? '') }}"
             data-btn-text="{{ e($slide->button_text ?? '') }}"
             data-btn-link="{{ e($slide->button_link ?? '') }}"
             data-btn2-text="{{ e($slide->button2_text ?? '') }}"
             data-btn2-link="{{ e($slide->button2_link ?? '') }}"></div>
    @empty
        <div class="hero-full-bg"
             style="background:linear-gradient(135deg,#3b2412 0%,#2c1a0a 60%,#1a1008 100%);"></div>
    @endforelse

    <div class="hero-full-overlay"></div>

    <div class="hero-full-body container">
        <div class="row justify-content-start">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="hero-full-content hero-full-content--stagger">
                    <p class="hero-eyebrow-dots hero-eyebrow-dots--pill" id="heroEyebrow">
                        @if($firstSlide && $firstSlide->subtitle)
                            {{ $firstSlide->subtitle }}
                        @else
                            {{ $heroEyebrowDefault }}
                        @endif
                    </p>
                    <h1 class="hero-full-title hero-full-title--display {{ ($firstSlide && $firstSlide->usesHeroTitleLines()) || !$firstSlide ? 'hero-full-title--lines' : '' }}" id="heroTitle">
                        @if(!$firstSlide)
                            <span class="hero-title-line">Luxury Venetian</span>
                            <span class="hero-title-line">Plaster <span class="hero-amp">&amp;</span> Bespoke</span>
                            <span class="hero-title-line">Media Walls</span>
                        @elseif($firstSlide->usesHeroTitleLines())
                            @foreach($firstSlide->heroHeadlineLines() as $heroLine)
                                <span class="hero-title-line">{{ $heroLine }}</span>
                            @endforeach
                        @else
                            {{ $firstSlide->title }}
                        @endif
                    </h1>
                    <p class="hero-full-sub" id="heroLead">{{ $firstSlide && filled($firstSlide->lead_text) ? $firstSlide->lead_text : $heroLeadDefault }}</p>
                    @if($sliders->isNotEmpty())
                        @php
                            $showHeroPrimary = filled(trim((string) ($firstSlide->button_text ?? '')));
                            $showHeroSecondary = filled(trim((string) ($firstSlide->button2_text ?? '')));
                        @endphp
                        <div class="hero-full-btns">
                            <a href="{{ $showHeroPrimary ? \App\Support\CmsOutboundHref::resolve($firstSlide->button_link ?? null, 'contact') : '#' }}"
                               class="hero-btn hero-btn--gold {{ $showHeroPrimary ? '' : 'd-none' }}"
                               id="heroBtnPrimary"
                               @unless($showHeroPrimary) aria-hidden="true" @endunless>
                                <span id="heroBtnText">@if($showHeroPrimary){{ $firstSlide->button_text }}@endif</span>
                                <i class="fa-solid fa-arrow-up-right" style="font-size:.72rem;" aria-hidden="true"></i>
                            </a>
                            <a href="{{ $showHeroSecondary ? \App\Support\CmsOutboundHref::resolve($firstSlide->button2_link ?? null, 'contact') : '#' }}"
                               class="hero-btn-outline hero-btn-outline--hero {{ $showHeroSecondary ? '' : 'd-none' }}"
                               id="heroBtnSecondary"
                               @unless($showHeroSecondary) aria-hidden="true" @endunless>
                                <span id="heroBtnSecondaryText">@if($showHeroSecondary){{ $firstSlide->button2_text }}@endif</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($sliders->count() > 1)
    <div class="hero-dots" role="tablist" aria-label="Hero slides">
        @foreach($sliders as $i => $s)
            <button type="button" class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Slide {{ $i + 1 }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
        @endforeach
    </div>
    @endif

    <div class="hero-scroll-cue" aria-hidden="true">
        <span class="hero-scroll-label">Explore</span>
        <span class="hero-scroll-line"></span>
    </div>

</section>
