@if($testimonials->count())
<section class="testi-split-section reveal" aria-labelledby="testi-split-heading">
    <h2 id="testi-split-heading" class="visually-hidden">Customer reviews</h2>
    <div class="testi-split-shell">
        <div class="testi-track testi-split-track" id="testiTrack">
            @foreach($testimonials as $t)
                @php
                    $msg = trim((string) $t->message);
                    $sentences = preg_split('/(?<=[.!?])\s+/u', $msg, -1, PREG_SPLIT_NO_EMPTY);
                    $sentences = array_values(array_filter(array_map('trim', $sentences)));
                    if (count($sentences) >= 2) {
                        $quoteLeft = $sentences[0];
                        if (Str::length($quoteLeft) > 220) {
                            $quoteLeft = Str::limit($quoteLeft, 180, '…');
                        }
                        $msgRight = trim(implode(' ', array_slice($sentences, 1)));
                    } else {
                        $quoteLeft = '';
                        $msgRight = $msg;
                    }
                    $photoUrl = trim((string) ($t->client_image_url ?? ''));
                    $photoBgCss = $photoUrl !== ''
                        ? 'background-image:url(\''.addcslashes($photoUrl, "'\\").'\');'
                        : 'background:#1a1510;';
                    $leftTitle = $t->client_company ?: $t->client_name;
                    $panelRole = collect([$t->client_position, $t->client_company])->filter()->implode(', ');
                @endphp
            <article class="testi-slide testi-split-slide">
                <div class="testi-split">
                    <div class="testi-split__photo"
                         style="{{ $photoBgCss }}">
                        <div class="testi-split__photo-scrim" aria-hidden="true"></div>
                        <div class="testi-split__photo-inner{{ $quoteLeft === '' ? ' testi-split__photo-inner--attrib-only' : '' }}">
                            @if($quoteLeft !== '')
                            <p class="testi-split__quote-short">&ldquo;{{ $quoteLeft }}&rdquo;</p>
                            @endif
                            <div class="testi-split__photo-attrib">
                                <p class="testi-split__photo-name">{{ $leftTitle }}</p>
                                @if($t->client_position)
                                    <p class="testi-split__photo-role">{{ strtoupper($t->client_position) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="testi-split__panel">
                        <div class="testi-split__panel-deco" aria-hidden="true"></div>
                        <p class="testi-split__kicker">Customer reviews</p>
                        <blockquote class="testi-split__quote-full">
                            <p>&ldquo;{{ $msgRight }}&rdquo;</p>
                        </blockquote>
                        <p class="testi-split__panel-name">{{ $t->client_name }}</p>
                        @if($panelRole !== '')
                            <p class="testi-split__panel-role">{{ strtoupper($panelRole) }}</p>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <div class="testi-split__seam-badge" aria-hidden="true">
            <span class="testi-split__seam-icon">&ldquo;</span>
        </div>

        @if($testimonials->count() > 1)
        <button type="button" class="testi-split-nav testi-split-nav--prev" id="testiPrev" aria-label="Previous testimonial">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="testi-split-nav testi-split-nav--next" id="testiNext" aria-label="Next testimonial">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="testi-split__dots-wrap">
            <div class="testi-dots testi-dots--split" id="testiDots" role="tablist" aria-label="Choose testimonial">
                @foreach($testimonials as $i => $t)
                <button type="button" class="testi-dot testi-dot--pill {{ $i === 0 ? 'active' : '' }}" data-idx="{{ $i }}" aria-label="Testimonial {{ $i + 1 }}" role="tab"></button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif
