@php
    $contactBandCfg = $contactBandSection ?? [];
    $contactBandEnabled = array_key_exists('is_enabled', $contactBandCfg) ? !empty($contactBandCfg['is_enabled']) : true;
    $contactBandEyebrow = $contactBandCfg['eyebrow'] ?? 'Contact Us';
    $contactBandHeading = $contactBandCfg['heading'] ?? 'How we can help?';
    $contactBandPanelTitle = $contactBandCfg['panel_title'] ?? 'Contact Us';
    $contactBandNamePlaceholder = $contactBandCfg['name_placeholder'] ?? 'Your Name';
    $contactBandEmailPlaceholder = $contactBandCfg['email_placeholder'] ?? 'Email';
    $contactBandPhonePlaceholder = $contactBandCfg['phone_placeholder'] ?? 'Phone(Optional)';
    $contactBandMessagePlaceholder = $contactBandCfg['message_placeholder'] ?? 'Tell us about your space';
    $contactBandSubmitText = $contactBandCfg['submit_text'] ?? 'Send Enquiry';
    $contactBandSubject = $contactBandCfg['subject'] ?? 'Website enquiry (home)';
    $contactBandVisualImage = !empty($contactBandCfg['visual_image'])
        ? asset('storage/' . $contactBandCfg['visual_image'])
        : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=900&q=80';
@endphp

@if($contactBandEnabled)
<section class="home-contact-band section-white" id="home-contact">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="eyebrow">{{ $contactBandEyebrow }}</span>
            <h2 class="home-contact-band__title mt-2 mb-0">{{ $contactBandHeading }}</h2>
        </div>
        <div class="row g-4 g-xl-5 align-items-stretch">
            <div class="col-lg-5 reveal-left d-none d-lg-block">
                <div class="home-contact-band__visual" aria-hidden="true">
                    <span class="home-contact-band__corner home-contact-band__corner--tl"></span>
                    <span class="home-contact-band__corner home-contact-band__corner--br"></span>
                    <img src="{{ $contactBandVisualImage }}"
                         alt=""
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy" decoding="async"
                         >
                </div>
            </div>
            <div class="col-lg-7 reveal-right">
                <div class="home-contact-panel">
                    <h3 class="home-contact-panel__title">{{ $contactBandPanelTitle }}</h3>
                    <div id="homeContactFormAjaxFeedback" class="d-none" role="status" aria-live="polite"></div>
                    @if (session('success'))
                    <div class="alert alert-success mb-3 small" role="status">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger mb-3 small">Please check the form and try again.</div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="home-contact-form"
                          data-contact-ajax="1"
                          data-feedback-id="homeContactFormAjaxFeedback"
                          data-error-intro="Please check the form and try again.">
                        @csrf
                        <input type="hidden" name="_form_context" value="home">
                        <div class="mb-3">
                            <input type="text" name="name" id="home_contact_name" required
                                   value="{{ old('name') }}"
                                   class="form-control home-contact-input @error('name') is-invalid @enderror"
                                   placeholder="{{ $contactBandNamePlaceholder }}"
                                   autocomplete="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" id="home_contact_email" required
                                   value="{{ old('email') }}"
                                   class="form-control home-contact-input @error('email') is-invalid @enderror"
                                   placeholder="{{ $contactBandEmailPlaceholder }}"
                                   autocomplete="email">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1" for="home_contact_phone_national">{{ $contactBandPhonePlaceholder }}</label>
                            @include('partials.intl-phone-field', [
                                'countries' => $phoneCountries ?? collect(),
                                'mode' => 'combined',
                                'namePhone' => 'phone',
                                'combinedPhoneValue' => old('phone'),
                                'defaultIso' => 'GB',
                                'instanceId' => 'home_contact_phone',
                                'nationalPlaceholder' => 'Phone number',
                                'nationalMaxLength' => 20,
                                'invalid' => $errors->has('phone'),
                            ])
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <input type="hidden" name="subject" value="{{ $contactBandSubject }}">
                        <div class="mb-4">
                            <textarea name="message" id="home_contact_message" rows="1" required minlength="10"
                                      placeholder="{{ $contactBandMessagePlaceholder }}"
                                      class="form-control home-contact-input @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="home-contact-submit w-100">
                            {{ $contactBandSubmitText }} <span aria-hidden="true">↗</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
