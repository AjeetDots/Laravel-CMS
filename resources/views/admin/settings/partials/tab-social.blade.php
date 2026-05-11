<div class="mb-0">
    <h2 class="h5 mb-3">Social</h2>
    <p class="text-muted small mb-4">Optional links displayed where your theme shows social icons.</p>
    <div class="mb-3">
        <label class="form-label" for="social_facebook"><i class="fab fa-facebook-f me-2 text-primary" aria-hidden="true"></i>Facebook URL</label>
        <input type="url" name="social_facebook" id="social_facebook" class="form-control @error('social_facebook') is-invalid @enderror" value="{{ old('social_facebook', $settings->get('social_facebook')) }}" placeholder="https://facebook.com/...">
        @error('social_facebook')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label" for="social_twitter"><i class="fab fa-twitter me-2" style="color:#1da1f2;" aria-hidden="true"></i>Twitter URL</label>
        <input type="url" name="social_twitter" id="social_twitter" class="form-control @error('social_twitter') is-invalid @enderror" value="{{ old('social_twitter', $settings->get('social_twitter')) }}" placeholder="https://twitter.com/...">
        @error('social_twitter')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label" for="social_linkedin"><i class="fab fa-linkedin-in me-2" style="color:#0077b5;" aria-hidden="true"></i>LinkedIn URL</label>
        <input type="url" name="social_linkedin" id="social_linkedin" class="form-control @error('social_linkedin') is-invalid @enderror" value="{{ old('social_linkedin', $settings->get('social_linkedin')) }}" placeholder="https://linkedin.com/...">
        @error('social_linkedin')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-0">
        <label class="form-label" for="social_instagram"><i class="fab fa-instagram me-2" style="color:#e1306c;" aria-hidden="true"></i>Instagram URL</label>
        <input type="url" name="social_instagram" id="social_instagram" class="form-control @error('social_instagram') is-invalid @enderror" value="{{ old('social_instagram', $settings->get('social_instagram')) }}" placeholder="https://instagram.com/...">
        @error('social_instagram')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>
