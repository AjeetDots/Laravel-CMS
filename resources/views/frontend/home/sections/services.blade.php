@php
    $servicesCfg = $servicesSection ?? [];
    $servicesEnabled = \App\Support\CmsModuleVisibility::isEnabled('services')
        && (array_key_exists('is_enabled', $servicesCfg) ? !empty($servicesCfg['is_enabled']) : true);
    $servicesEyebrow = $servicesCfg['eyebrow'] ?? '';
    $servicesHeadingLine1 = $servicesCfg['heading_line_1'] ?? '';
    $servicesHeadingLine2 = $servicesCfg['heading_line_2'] ?? '';
    $servicesBtnText = $servicesCfg['button_text'] ?? '';
    $servicesBtnUrl = $servicesCfg['button_url'] ?? '';
@endphp

@if($servicesEnabled)
<section class="section section-soft disciplines-section">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7 reveal-left">
                <span class="finishes-intro__eyebrow">{{ $servicesEyebrow }}</span>
                <h2 class="home-atelier-headline">
                    {{ $servicesHeadingLine1 }}<br>{{ $servicesHeadingLine2 }}
                </h2>
            </div>
            <div class="col-lg-4 offset-lg-1 text-lg-end mt-3 mt-lg-0 reveal-right secondary-button">
                @if(trim($servicesBtnUrl) !== '' && trim($servicesBtnText) !== '')
                <a href="{{ $servicesBtnUrl }}" class="btn-outline-quote-dark hover-gold ">
                    {{ $servicesBtnText }} <i class="fas fa-arrow-right ms-1" ></i>
                </a>
                @endif
            </div>
        </div>

        <div class="row g-4">
            @forelse($services->take(3) as $i => $service)
            @php
                $hoverImageUrl = filled($service->hover_image) ? $service->hover_image_url : null;
                $hoverTitle = $service->resolvedHoverTitle();
            @endphp
            <div class="col-md-4 reveal delay-{{ $i + 1 }}">
                <a href="{{ route('services.show', $service->slug) }}" class="disc-card @if($hoverTitle) disc-card--has-hover-title @endif">
                    <div class="disc-card-img-wrap @if($hoverImageUrl) disc-card-img-wrap--has-hover @endif">
                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="disc-card-img default-img" loading="lazy">
                        @if($hoverImageUrl)
                            <img src="{{ $hoverImageUrl }}" alt="{{ $hoverTitle ?? $service->title }}" class="disc-card-img hover-img" loading="lazy">
                        @endif
                        <div class="disc-card-overlay"></div>
                    </div>
                    <div class="disc-card-body">
                        <h4 class="home-why-card__title-dark disc-card-title @if($hoverTitle) disc-card-title--has-hover @endif">
                            <span class="disc-card-title__default">{{ $service->title }}</span>
                            @if($hoverTitle)
                                <span class="disc-card-title__hover">{{ $hoverTitle }}</span>
                            @endif
                        </h4>
                        @if($service->short_description)
                            <p class="disc-card-desc">{{ $service->short_description }}</p>
                        @endif
                        <span class="disc-card-link">
                             <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
@endif
