@extends('layouts.frontend')

@section('title', $page->meta_title ?? 'About Us')

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('styles')
<link href="{{ asset('css/about.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">About us</span>
        <h1 class="page-hero-title-wide">Mastery in plaster, finish &amp; form.</h1>
        <p>{{ $settings->get('site_name', 'Bespoke Ornate Plaster') }} brings hand-applied surfaces and bespoke interiors to discerning residential and commercial clients — where material, light, and detail meet.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">About Us</li>
            </ol>
        </nav>
    </div>
</div>
<section class="about-feature">
    <div class="container">
        <div class="row align-items-start g-5">
            @foreach($page->sections as $section)

                <section class="about-feature">
                    <div class="container">
                        <div class="row align-items-start g-5">

                            @include(
                                'frontend.sections.' . $section->type,
                                [
                                    'data' => $section->data
                                ]
                            )

                        </div>
                    </div>
                </section>

            @endforeach
        </div>
    </div>
</section>

{{-- ── FEATURE IMAGE + OUR STORY ──────────────────────────────────── --}}
<!-- <section class="about-feature">
    <div class="container">
        <div class="row align-items-start g-5">
            {{-- Image --}}
            <div class="col-lg-6">
                <div class="media-frame about-feature-frame">
                    <img
                        src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&q=80"
                        alt="Our team at work"
                        class="feature-img img-fallback"
                        data-fallback="https://placehold.co/600x800/e8e4dc/4a4a46?text=Our+Team"
                    >
                </div>
            </div>
            {{-- Story --}}
            <div class="col-lg-6" style="padding-top: 60px;">
                <span class="story-label">Our story</span>
                <h2>Built on craft,<br>refined by experience.</h2>
                <p>For decades we have collaborated with designers, architects, and homeowners who expect surfaces and volumes finished to an exceptional standard — Venetian plaster, cornices, feature walls, and restoration work carried out by specialist artisans.</p>
                <p>From boutique hospitality to private residences, every commission is approached as a piece of the whole: proportion, texture, and light working together.</p>
                <p>Every project begins with listening — your space, your timeline, your vision — and ends with a finish meant to endure.</p>

                {{-- Quick facts --}}
                <div class="row g-3 mt-3">
                    <div class="col-6">
                        <div style="border-top: 2px solid var(--gold); padding-top: 16px;">
                            <div style="font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -1px;">10+</div>
                            <div style="font-size: .82rem; color: var(--ink-light); margin-top: 4px;">Years of experience</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="border-top: 2px solid var(--gold); padding-top: 16px;">
                            <div style="font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -1px;">500+</div>
                            <div style="font-size: .82rem; color: var(--ink-light); margin-top: 4px;">Projects delivered</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="border-top: 2px solid var(--gold); padding-top: 16px;">
                            <div style="font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -1px;">98%</div>
                            <div style="font-size: .82rem; color: var(--ink-light); margin-top: 4px;">Client satisfaction</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="border-top: 2px solid var(--gold); padding-top: 16px;">
                            <div style="font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -1px;">24/7</div>
                            <div style="font-size: .82rem; color: var(--ink-light); margin-top: 4px;">Support available</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

{{-- ── DARK STATS BAND ─────────────────────────────────────────────── --}}
<!-- <section class="about-stats">
    <div class="container">
        <div class="d-flex align-items-center justify-content-around flex-wrap gap-4">
            <div class="stat-block">
                <span class="num">10<span style="color: var(--gold);">+</span></span>
                <span class="lbl">Years active</span>
            </div>
            <div class="stat-sep d-none d-md-block"></div>
            <div class="stat-block">
                <span class="num">500<span style="color: var(--gold);">+</span></span>
                <span class="lbl">Projects completed</span>
            </div>
            <div class="stat-sep d-none d-md-block"></div>
            <div class="stat-block">
                <span class="num">98<span style="font-size:2rem; color: var(--gold);">%</span></span>
                <span class="lbl">Satisfaction rate</span>
            </div>
            <div class="stat-sep d-none d-md-block"></div>
            <div class="stat-block">
                <span class="num">30<span style="color: var(--gold);">+</span></span>
                <span class="lbl">Team members</span>
            </div>
        </div>
    </div>
</section> -->

{{-- ── VALUES ──────────────────────────────────────────────────────── --}}
<!-- <section class="about-values">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 values-header">
                <span class="section-tag">What we stand for</span>
                <h2>Our values<br>guide everything.</h2>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-lg-6">
                <div class="value-card pe-lg-5">
                    <span class="value-num">01</span>
                    <h4>Craftsmanship over convenience</h4>
                    <p>We resist shortcuts. Every solution we deliver is designed with precision, tested under real conditions, and built to outlast trends.</p>
                </div>
                <div class="value-card pe-lg-5">
                    <span class="value-num">02</span>
                    <h4>Honesty in every conversation</h4>
                    <p>We say what we mean and deliver what we promise. Transparent timelines, realistic estimates, and no hidden surprises.</p>
                </div>
                <div class="value-card pe-lg-5">
                    <span class="value-num">03</span>
                    <h4>Long-term thinking</h4>
                    <p>We don't chase contracts — we build relationships. Our best clients have been with us for years because we prioritize their long-term success.</p>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="value-card">
                    <span class="value-num">04</span>
                    <h4>Collaboration at every stage</h4>
                    <p>We integrate with your team, not just as vendors but as partners. Your insights shape our direction at every milestone.</p>
                </div>
                <div class="value-card">
                    <span class="value-num">05</span>
                    <h4>Relentless improvement</h4>
                    <p>Technology moves fast. We stay ahead through continuous learning, internal R&D, and a culture that treats improvement as a default — not an exception.</p>
                </div>
                <div class="value-card">
                    <span class="value-num">06</span>
                    <h4>Accountability</h4>
                    <p>If something goes wrong, we own it. We diagnose, fix, and make it right — fast. Our reputation is built on how we handle difficulty, not just success.</p>
                </div>
            </div>
        </div>
    </div>
</section> -->

{{-- ── TEAM ────────────────────────────────────────────────────────── --}}
<!-- <section class="about-team">
    <div class="container">
        <span class="team-label">The people behind the work</span>
        <h2>Meet our leadership.</h2>
        <div class="row g-4">
            @php
                $team = [
                    ['name' => 'James Harrington', 'role' => 'Founder & CEO', 'initials' => 'JH'],
                    ['name' => 'Sophia Callahan',  'role' => 'Head of Design',  'initials' => 'SC'],
                    ['name' => 'Marcus Webb',      'role' => 'Lead Engineer',   'initials' => 'MW'],
                    ['name' => 'Priya Nair',       'role' => 'Project Director','initials' => 'PN'],
                ];
            @endphp
            @foreach($team as $member)
            <div class="col-6 col-lg-3">
                <div class="team-card">
                    <div class="team-img">
                        <span class="initials">{{ $member['initials'] }}</span>
                    </div>
                    <h5>{{ $member['name'] }}</h5>
                    <span>{{ $member['role'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section> -->

{{-- ── CTA ─────────────────────────────────────────────────────────── --}}
<section class="about-cta">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2>Ready to work<br>together?</h2>
                <p>Whether you have a clear brief or a rough idea, we're happy to sit down, listen, and figure out the right path forward — no obligation.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-ink">
                        Get in touch <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                    </a>
                    <a href="{{ route('services') }}" class="btn-outline-ink">
                        View services
                    </a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div style="background: #f2ede5; border-radius: 4px; padding: 40px;">
                    <div style="font-size: .7rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 20px;">Contact us directly</div>
                    <div style="margin-bottom: 16px;">
                        <div style="font-size: .8rem; color: var(--ink-light); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Email</div>
                        <a href="mailto:{{ $settings->get('site_email', 'info@bespokeornateplaster.com') }}" style="font-size: 1rem; color: var(--ink); font-weight: 700; text-decoration: none;">
                            {{ $settings->get('site_email', 'info@bespokeornateplaster.com') }}
                        </a>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <div style="font-size: .8rem; color: var(--ink-light); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Phone</div>
                        <a href="tel:{{ $settings->get('site_phone', '') }}" style="font-size: 1rem; color: var(--ink); font-weight: 700; text-decoration: none;">
                            {{ $settings->get('site_phone', '+1 (555) 123-4567') }}
                        </a>
                    </div>
                    <div>
                        <div style="font-size: .8rem; color: var(--ink-light); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Address</div>
                        <p style="font-size: .95rem; color: var(--ink); font-weight: 600; margin: 0; line-height: 1.5;">
                            {{ $settings->get('site_address', '123 Business Ave, New York') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script>
document.querySelectorAll('img.img-fallback').forEach(function(img) {
    img.addEventListener('error', function() {
        var fb = this.getAttribute('data-fallback');
        if (fb) { this.src = fb; }
    });
});
</script>
@endsection
