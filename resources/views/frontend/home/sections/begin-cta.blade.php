<section class="home-begin-cta position-relative">
    <div class="home-begin-cta__bg" style="background-image:url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80');"></div>
    <div class="home-begin-cta__overlay"></div>
    <div class="container position-relative text-center home-begin-cta__inner">
        <span class="home-begin-cta__eyebrow">Begin a Project</span>
        <h2 class="home-begin-cta__title">Transform your space <br />into a quiet masterpiece.</h2>
        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
            <a href="{{ route('contact') }}" class="hero-btn hero-btn--gold">Get free consultation</a>
            <a href="tel:{{ $settings->get('site_phone') }}" class="hero-btn-outline hero-btn-outline--hero home-begin-cta__ghost">
                <i class="fas fa-phone"></i>
                Call the studio
            </a>
        </div>
    </div>
</section>
