@php
    $commissionsCfg = $commissionsSection ?? [];
    $commissionsEnabled = array_key_exists('is_enabled', $commissionsCfg) ? ! empty($commissionsCfg['is_enabled']) : true;
    $commissionsEyebrow = $commissionsCfg['eyebrow'] ?? '';
    $commissionsHeadingLine1 = $commissionsCfg['heading_line_1'] ?? '';
    $commissionsBtnText = $commissionsCfg['button_text'] ?? '';
    $commissionsBtnUrl = $commissionsCfg['button_url'] ?? '';
    $commissionsBtnUrlStr = $commissionsBtnUrl !== null ? trim((string) $commissionsBtnUrl) : '';
@endphp

@if($gallery->isNotEmpty() && $commissionsEnabled)
    @php $commissionItems = $gallery->take(8); @endphp
    <section class="commissions-section">
        <div class="container">

            <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
                <div class="reveal-left">
                    <span class="finishes-intro__eyebrow">{{ $commissionsEyebrow }}</span>
                    <h2 class="home-atelier-headline" style="margin:10px 0 0;">
                        {{ $commissionsHeadingLine1 }}
                    </h2>
                </div>
                <div class="reveal-right d-flex flex-wrap gap-2 justify-content-lg-end">
                    @if(trim((string) $commissionsBtnText) !== '')
                        <a href="{{ \App\Support\CmsOutboundHref::resolve($commissionsBtnUrlStr !== '' ? $commissionsBtnUrlStr : null, 'gallery') }}" class="hero-btn hero-btn--gold home-atelier-btn">
                            {{ $commissionsBtnText }}
                            <i class="fa-solid fa-arrow-up-right" style="font-size:.72rem;" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="commissions-grid reveal recent-commissions">
                @foreach($commissionItems as $item)
                    <a href="{{ route('gallery') }}" class="commission-item">
                        <img src="{{ $item->image_url }}"
                             alt="{{ $item->title ?? '' }}"
                             class="commission-img"
                             loading="lazy"
                             decoding="async">
                        <div class="commission-overlay"></div>
                        <div class="commission-body">
                            <div class="commission-meta">
                                @if($item->galleryCategory)
                                    <span class="review-label">{{ $item->galleryCategory->name }}</span>
                                @endif
                                @if(trim((string) ($item->title ?? '')) !== '')
                                    <h4 class="home-why-card__title">{{ $item->title }}</h4>
                                @endif
                                @if(filled($item->section_content))
                                    <p class="commission-title commission-text mb-0 text-white">{!! nl2br(e($item->section_content)) !!}</p>
                                @endif
                            </div>
                            <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>
@endif
