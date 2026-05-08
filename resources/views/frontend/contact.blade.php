@extends('layouts.frontend')
@section('title', 'Contact Us')
@section('body_class', 'nav-solid page-contact')
@section('content')

@php
    $contactHeroImage = \App\Models\GalleryItem::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->value('image');
    $contactHeroUrl = $contactHeroImage
        ? (filter_var($contactHeroImage, FILTER_VALIDATE_URL) ? $contactHeroImage : asset('storage/' . $contactHeroImage))
        : null;
@endphp

<section class="contact-hero" @if($contactHeroUrl) style="--contact-hero-image:url('{{ $contactHeroUrl }}');" @endif>
    <div class="contact-hero__overlay"></div>
    <div class="container contact-hero__inner">
        <h1>Bring us your space.<br>We'll bring the finish.</h1>
        <a href="#contactFormPanel" class="contact-hero__btn">Get a quote <i class="fas fa-arrow-up-right"></i></a>
    </div>
</section>

<section class="contact-main">
    <div class="container">
        <div class="row g-4 g-xl-5 align-items-start">
            <div class="col-lg-6">
                <div class="contact-main__info">
                    <span class="contact-main__eyebrow">Contact</span>
                    <h2>Let's discuss<br>your project.</h2>
                    <p class="contact-main__lead">
                        Share a few details and a member of the studio will be in touch within one working day.
                        For urgent enquiries, please call or message us on WhatsApp.
                    </p>

                    <div class="contact-main__block">
                        <span class="contact-main__label">Studio</span>
                        <p class="contact-main__studio">
                            Bespoke Ornate Plaster<br>
                            London, United Kingdom
                        </p>
                    </div>

                    <div class="contact-main__lines">
                        <div><i class="fas fa-phone"></i> {{ $settings->get('site_phone','+1 (555) 123-4567') }}</div>
                        <div><i class="fab fa-whatsapp"></i> WhatsApp</div>
                        <div><i class="fas fa-envelope"></i> {{ $settings->get('site_email','info@bespokeornateplaster.com') }}</div>
                        <div><i class="far fa-clock"></i> By appointment</div>
                    </div>

                    <div class="contact-main__block">
                        <span class="contact-main__label">Hours</span>
                        <p class="contact-main__hours">Monday - Friday<br>09:00 - 18:00 GMT</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-main__form-panel" id="contactFormPanel">
                    <div class="contact-main__form-topline" aria-hidden="true"></div>
                    <h3>Contact Us</h3>
                    @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        Please fix the errors below and resubmit.
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="subject" value="{{ old('subject') }}">
                        <div class="contact-main__form-grid">
                            <input type="text" id="contact_name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Your Name"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <input type="email" id="contact_email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <input type="text" id="contact_phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Phone(Optional)"
                                value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <textarea id="contact_message" name="message"
                                class="form-control contact-main__message @error('message') is-invalid @enderror"
                                placeholder="Tell us about your space">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <button type="submit" class="contact-main__submit">
                                Send enquiry <i class="fas fa-arrow-up-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-map-wrap" aria-label="Map">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19800.055036896083!2d0.0806371033339485!3d52.193834501271906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d870a027de5493%3A0x40fdbfa6f7c3e20!2sCambridge%2C%20UK!5e0!3m2!1sen!2sin!4v1778150755661!5m2!1sen!2sin"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen=""
    ></iframe>
</section>

@endsection
