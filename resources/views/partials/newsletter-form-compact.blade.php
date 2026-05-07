{{-- Single newsletter signup (footer only — avoid duplicating with CTA) --}}
<div class="footer-newsletter" id="footer-newsletter">
    <h6 class="footer-col-title" id="footer-newsletter-heading">Newsletter</h6>
    <p class="footer-newsletter__lead">Occasional projects, tips, and offers. Unsubscribe anytime.</p>
    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="footer-newsletter-form" aria-labelledby="footer-newsletter-heading">
        @csrf
        <label class="visually-hidden" for="footer-newsletter-email">Email address</label>
        <input type="email"
               id="footer-newsletter-email"
               name="email"
               value="{{ old('email') }}"
               placeholder="Your email"
               class="footer-newsletter-input"
               required
               autocomplete="email">
        @error('email')
            <span class="footer-newsletter-error" role="alert">{{ $message }}</span>
        @enderror
        <button type="submit" class="footer-newsletter-btn">Subscribe</button>
    </form>
    <p class="footer-newsletter-privacy">
        <i class="fas fa-lock" aria-hidden="true"></i> We respect your privacy.
    </p>
</div>
