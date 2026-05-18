@if($testimonials->isNotEmpty())
@php
    $leftPanels = $testimonials->map(function ($t) {
        $msg = trim((string) $t->message);
        $sentences = preg_split('/(?<=[.!?])\s+/u', $msg, -1, PREG_SPLIT_NO_EMPTY);
        $sentences = array_values(array_filter(array_map('trim', $sentences)));
        if (count($sentences) >= 1) {
            $leftHeadline = $sentences[0];
            if (\Illuminate\Support\Str::length($leftHeadline) > 220) {
                $leftHeadline = \Illuminate\Support\Str::limit($leftHeadline, 180, '…');
            }
        } else {
            $leftHeadline = \Illuminate\Support\Str::limit($msg, 180, '…');
        }

        return [
            'photo' => $t->client_image_url,
            'alt' => $t->client_name,
            'headline' => $leftHeadline,
            'project' => trim((string) ($t->client_company ?? '')),
            'designation' => trim((string) ($t->client_position ?? '')),
        ];
    })->values()->all();

    $featured = $testimonials->first();
    $featuredPhoto = $featured->client_image_url;
    $msgFeatured = trim((string) $featured->message);
    $sentencesF = preg_split('/(?<=[.!?])\s+/u', $msgFeatured, -1, PREG_SPLIT_NO_EMPTY);
    $sentencesF = array_values(array_filter(array_map('trim', $sentencesF)));
    if (count($sentencesF) >= 1) {
        $leftHeadline0 = $sentencesF[0];
        if (\Illuminate\Support\Str::length($leftHeadline0) > 220) {
            $leftHeadline0 = \Illuminate\Support\Str::limit($leftHeadline0, 180, '…');
        }
    } else {
        $leftHeadline0 = \Illuminate\Support\Str::limit($msgFeatured, 180, '…');
    }
    $featuredProject = trim((string) ($featured->client_company ?? ''));
    $featuredDesignation = trim((string) ($featured->client_position ?? ''));

    $testimonialsCfg = $testimonialsSection ?? [];
    $leftPanelEyebrow = trim((string) ($testimonialsCfg['left_eyebrow'] ?? ''));
    $leftPanelHeadline = trim((string) ($testimonialsCfg['left_headline'] ?? ''));
    $rightPanelEyebrow = trim((string) ($testimonialsCfg['right_eyebrow'] ?? ''));
    $leftPanelImage = $testimonialsCfg['left_image_url'] ?? asset('images/testimonial.jpg');
    if ($leftPanelEyebrow === '') {
        $leftPanelEyebrow = 'Testimonial';
    }
    if ($leftPanelHeadline === '') {
        $leftPanelHeadline = 'Luxury Experiences Shared';
    }
    if ($rightPanelEyebrow === '') {
        $rightPanelEyebrow = 'Customer Reviews';
    }
    $hasLeftPanelImage = filled($testimonialsCfg['left_image_url'] ?? null);
@endphp
<script type="application/json" id="testimonialLeftPanelsData">@json($leftPanels)</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<style>
    .swiper-pagination {
        position: relative;
        margin-top: 60px;
        text-align: left;
    }

    .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #fff;
        opacity: .4;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
    }
</style>
<section class="testimonial-section container-fluid p-0">
    <div class="row g-0 align-items-stretch">
        <div class="col-lg-6">
            <div class="testimonial-left">
                <img src="{{ $leftPanelImage }}" class="img-fluid" alt="" />
                <div class="testimonial-left__photo-fallback"
                     id="testimonialLeftFallback"
                     role="presentation"
                     @if($hasLeftPanelImage) style="display:none" @endif></div>
                <div class="left-content">
                    <div class="review-label mb-4">{{ $leftPanelEyebrow }}</div>
                    <h2 class="home-atelier-headline-white">{{ $leftPanelHeadline }}</h2>
                    <!-- <div class="project" id="testimonialLeftProject" @unless($featuredProject !== '') style="display:none" @endunless>{{ $featuredProject }}</div>
                    <div class="designation" id="testimonialLeftDesignation" @unless($featuredDesignation !== '') style="display:none" @endunless>{{ $featuredDesignation }}</div> -->
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
                                    <h3>&ldquo;{{ $t->message }}&rdquo;</h3>
                                    <div class="d-flex gap-3 align-items-center ">
                                        <div><img src="{{ $featuredPhoto ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                                                            alt="{{ $featured->client_name }}"
                                                            class="testi-img"
                                                            id="testimonialLeftImg"
                                                            @unless($featuredPhoto) style="display:none" @endunless
                                                            loading="eager"
                                                            decoding="async" />
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
        var dataEl = document.getElementById('testimonialLeftPanelsData');
        var panels = [];
        if (dataEl && dataEl.textContent) {
            try {
                panels = JSON.parse(dataEl.textContent);
            } catch (e) {
                panels = [];
            }
        }

        var img = document.getElementById('testimonialLeftImg');
        var fallback = document.getElementById('testimonialLeftFallback');
        var quoteEl = document.getElementById('testimonialLeftQuote');
        var projectEl = document.getElementById('testimonialLeftProject');
        var designationEl = document.getElementById('testimonialLeftDesignation');

        function applyLeftPanel(index) {
            if (!panels.length) return;
            var i = index;
            if (i < 0 || i >= panels.length) {
                i = 0;
            }
            var p = panels[i];
            if (!p) return;

            var raw = p.photo;
            var photo = (raw != null && String(raw).trim() !== '') ? String(raw).trim() : '';
            if (photo && img) {
                img.src = photo;
                img.alt = p.alt ? String(p.alt) : '';
                img.style.display = '';
                if (fallback) fallback.style.display = 'none';
            } else {
                if (img) img.style.display = 'none';
                if (fallback) fallback.style.display = '';
            }

            if (quoteEl) {
                quoteEl.innerHTML = '\u201C' + escapeHtml(String(p.headline || '')) + '\u201D';
            }
            if (projectEl) {
                var proj = String(p.project || '').trim();
                projectEl.textContent = proj;
                projectEl.style.display = proj !== '' ? '' : 'none';
            }
            if (designationEl) {
                var des = String(p.designation || '').trim();
                designationEl.textContent = des;
                designationEl.style.display = des !== '' ? '' : 'none';
            }
        }

        function escapeHtml(s) {
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        var swiper = new Swiper('.testimonial-slider', {
            loop: multi,
            speed: 900,
            autoplay: multi ? { delay: 4000, disableOnInteraction: false } : false,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            effect: 'slide',
            on: {
                slideChange: function () {
                    applyLeftPanel(this.realIndex);
                },
            },
        });

        applyLeftPanel(swiper.realIndex);
    })();
</script>
@endif
