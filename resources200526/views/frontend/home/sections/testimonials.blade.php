@if($testimonials->isNotEmpty())
@php
    $testimonialsCfg = $testimonialsSection ?? [];
    $leftPanelEyebrow = trim((string) ($testimonialsCfg['left_eyebrow'] ?? ''));
    $leftPanelHeadline = trim((string) ($testimonialsCfg['left_headline'] ?? ''));
    $rightPanelEyebrow = trim((string) ($testimonialsCfg['right_eyebrow'] ?? ''));
    $leftPanelImage = filled($testimonialsCfg['left_image_url'] ?? null)
        ? $testimonialsCfg['left_image_url']
        : \App\Support\CmsImage::defaultUrl();
    if ($leftPanelEyebrow === '') {
        $leftPanelEyebrow = 'Testimonial';
    }
    if ($leftPanelHeadline === '') {
        $leftPanelHeadline = 'Luxury Experiences Shared';
    }
    if ($rightPanelEyebrow === '') {
        $rightPanelEyebrow = 'Customer Reviews';
    }
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<style>
    .testimonial-section .swiper-pagination {
        position: relative;
        margin-top: 60px;
        text-align: left;
    }

    .testimonial-section .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: rgba(255, 255, 255, 0.45);
        opacity: 1;
    }

    .testimonial-section .swiper-pagination-bullet-active {
        background: #c4a06a;
        opacity: 1;
    }
</style>
<section class="testimonial-section container-fluid p-0">
    <div class="row g-0 align-items-stretch">
        <div class="col-lg-6">
            <div class="testimonial-left">
                <img src="{{ $leftPanelImage }}"
                     class="img-fluid"
                     alt=""
                     loading="lazy"
                     decoding="async">
                <div class="left-content">
                    <div class="review-label mb-4">{{ $leftPanelEyebrow }}</div>
                    <h2 class="home-atelier-headline-white">{{ $leftPanelHeadline }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-6 right-circle">
            <div class="testimonial-right">
                <div class="review-label">{{ $rightPanelEyebrow }}</div>
                <div class="swiper testimonial-slider">
                    <div class="swiper-wrapper">
                        @foreach($testimonials as $t)
                            @php
                                $roleParts = array_filter([
                                    trim((string) ($t->client_position ?? '')),
                                    trim((string) ($t->client_company ?? '')),
                                ]);
                                $roleLine = implode(' · ', $roleParts);
                            @endphp
                            <div class="swiper-slide">
                                <div class="testimonial-slide">
                                 <h3>{!! '&ldquo;' . $t->plain_message . '&rdquo;' !!}</h3>
                                    <div class="d-flex gap-3 align-items-center">
                                        <div>
                                            <img src="{{ $t->client_image_url }}"
                                                 alt="{{ $t->client_name }}"
                                                 class="testi-img"
                                                 loading="lazy"
                                                 decoding="async">
                                        </div>
                                        <div>
                                            <div class="client-name">{{ $t->client_name }}</div>
                                            @if($roleLine !== '')
                                                <div class="client-role">{{ $roleLine }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    (function () {
        var el = document.querySelector('.testimonial-slider');
        if (!el) return;
        var multi = {{ $testimonials->count() > 1 ? 'true' : 'false' }};

        new Swiper('.testimonial-slider', {
            loop: multi,
            speed: 900,
            autoplay: multi ? { delay: 4000, disableOnInteraction: false } : false,
            pagination: {
                el: '.testimonial-section .swiper-pagination',
                clickable: true,
            },
            effect: 'slide',
        });
    })();
</script>
@endif
