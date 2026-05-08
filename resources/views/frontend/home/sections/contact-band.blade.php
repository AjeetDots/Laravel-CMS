<section class="home-contact-band section-white" id="home-contact">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="eyebrow">Contact Us</span>
            <h2 class="home-contact-band__title mt-2 mb-0">How we can help?</h2>
        </div>
        <div class="row g-4 g-xl-5 align-items-stretch">
            <div class="col-lg-5 reveal-left d-none d-lg-block">
                <div class="home-contact-band__visual" aria-hidden="true">
                    <span class="home-contact-band__corner home-contact-band__corner--tl"></span>
                    <span class="home-contact-band__corner home-contact-band__corner--br"></span>
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=900&q=80"
                         alt=""
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy" decoding="async"
                         >
                </div>
            </div>
            <div class="col-lg-7 reveal-right">
                <div class="home-contact-panel">
                    <h3 class="home-contact-panel__title">Contact Us</h3>
                    @if($errors->any())
                    <div class="alert alert-danger mb-3 small">Please check the form and try again.</div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="home-contact-form">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" id="home_contact_name" required
                                   value="{{ old('name') }}"
                                   class="form-control home-contact-input @error('name') is-invalid @enderror"
                                   placeholder="Your Name"
                                   autocomplete="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" id="home_contact_email" required
                                   value="{{ old('email') }}"
                                   class="form-control home-contact-input @error('email') is-invalid @enderror"
                                   placeholder="Email"
                                   autocomplete="email">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" id="home_contact_phone"
                                   value="{{ old('phone') }}"
                                   class="form-control home-contact-input @error('phone') is-invalid @enderror"
                                   placeholder="Phone(Optional)"
                                   autocomplete="tel">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <input type="hidden" name="subject" value="Website enquiry (home)">
                        <div class="mb-4">
                            <textarea name="message" id="home_contact_message" rows="1" required minlength="10"
                                      placeholder="Tell us about your space"
                                      class="form-control home-contact-input @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="home-contact-submit w-100">
                            Send Enquiry <span aria-hidden="true">↗</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
