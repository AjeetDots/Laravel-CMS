@php
    $servicesCfg = $servicesSection ?? [];
    $servicesEnabled = array_key_exists('is_enabled', $servicesCfg) ? !empty($servicesCfg['is_enabled']) : true;
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
                <a href="{{ $servicesBtnUrl }}" class="hover-gold">
                    {{ $servicesBtnText }} <i class="fas fa-arrow-right ms-1" ></i>
                </a>
                @endif
            </div>
        </div>

        <div class="row g-4">
            @forelse($services->take(3) as $i => $service)
            <div class="col-md-4 reveal delay-{{ $i + 1 }}">
                <a href="{{ route('services.show', $service->slug) }}" class="disc-card">
                    <div class="disc-card-img-wrap">
                        @if($service->image)
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="disc-card-img">
                               <img src="../images/img-1.png" alt="" class="disc-card-img hover-img">
                        @else
                            <div class="disc-card-placeholder"><i class="fas fa-paint-brush"></i></div>
                        @endif
                        <div class="disc-card-overlay"></div>
                    </div>
                    <div class="disc-card-body">
                        <h4 class="home-why-card__title-dark">{{ $service->title }}</h4>
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
