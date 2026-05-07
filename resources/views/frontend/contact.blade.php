@extends('layouts.frontend')
@section('title', 'Contact Us')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Get in touch</span>
        <h1 class="page-hero-title-wide">Contact Us</h1>
        <p>Have a project in mind? Tell us what you're envisioning — we'll respond within 24 hours.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white contact-wrap">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-stretch">

            {{-- Info Panel --}}
            <div class="col-lg-4">
                <div class="contact-info-panel">

                    {{-- Availability badge --}}
                    <div class="cinfo-avail">
                        <span class="cinfo-avail-dot"></span>
                        Accepting new projects
                    </div>

                    {{-- Editorial heading --}}
                    <h2 class="cinfo-heading">Let's discuss<br><em>your project.</em></h2>
                    <p class="sub">Whether you have a brief or just an idea, we're happy to help figure out the right path forward.</p>

                    <div class="cinfo-divider"></div>

                    {{-- Contact rows --}}
                    <div class="cinfo-row">
                        <div class="cinfo-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="cinfo-label">Email</div>
                            <div class="cinfo-val">{{ $settings->get('site_email','info@bespokeornateplaster.com') }}</div>
                        </div>
                    </div>
                    <div class="cinfo-row">
                        <div class="cinfo-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <div class="cinfo-label">Phone</div>
                            <div class="cinfo-val">{{ $settings->get('site_phone','+1 (555) 123-4567') }}</div>
                        </div>
                    </div>
                    <div class="cinfo-row">
                        <div class="cinfo-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="cinfo-label">Address</div>
                            <div class="cinfo-val">{{ $settings->get('site_address','123 Business Ave, New York') }}</div>
                        </div>
                    </div>

                    {{-- Social --}}
                    @if($settings->get('social_facebook') || $settings->get('social_twitter') || $settings->get('social_linkedin'))
                    <div style="margin-top:auto;padding-top:36px;">
                        <p class="cinfo-social-label">Follow us</p>
                        <div class="cinfo-social-links">
                            @if($settings->get('social_facebook'))
                            <a href="{{ $settings->get('social_facebook') }}" target="_blank" rel="noopener" class="cinfo-social-link" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            @endif
                            @if($settings->get('social_twitter'))
                            <a href="{{ $settings->get('social_twitter') }}" target="_blank" rel="noopener" class="cinfo-social-link" aria-label="Twitter/X">
                                <i class="fab fa-twitter"></i>
                            </a>
                            @endif
                            @if($settings->get('social_linkedin'))
                            <a href="{{ $settings->get('social_linkedin') }}" target="_blank" rel="noopener" class="cinfo-social-link" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Form Panel --}}
            <div class="col-lg-8">
                <div class="contact-form-panel">

                    <p class="cfp-eyebrow">Send a message</p>
                    <h3>Tell us about your project.</h3>
                    <p class="sub">Fill in the form below and our team will get back to you within 24 hours.</p>

                    <div class="cfp-heading-deco">
                        <div class="cfp-heading-deco-dot"></div>
                        <div class="cfp-heading-deco-line"></div>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        Please fix the errors below and resubmit.
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="contact_name" class="form-label">Full Name <span style="color:var(--wine)">*</span></label>
                                <input type="text" id="contact_name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="John Anderson"
                                       value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_email" class="form-label">Email Address <span style="color:var(--wine)">*</span></label>
                                <input type="email" id="contact_email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="john@company.com"
                                       value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_phone" class="form-label">Phone Number</label>
                                <input type="text" id="contact_phone" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="+1 (555) 000-0000"
                                       value="{{ old('phone') }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_subject" class="form-label">Subject</label>
                                <input type="text" id="contact_subject" name="subject"
                                       class="form-control @error('subject') is-invalid @enderror"
                                       placeholder="Project enquiry"
                                       value="{{ old('subject') }}">
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="contact_message" class="form-label">Message <span style="color:var(--wine)">*</span></label>
                                <textarea id="contact_message" name="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          placeholder="Tell us about your project, timeline, and budget...">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 mt-2 d-flex align-items-center gap-4 flex-wrap">
                                <button type="submit" class="contact-submit-btn">
                                    Send Enquiry
                                    <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
