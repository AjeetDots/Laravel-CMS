@extends('layouts.frontend')
@section('title', 'Contact')
@section('body_class', 'nav-solid page-contact')
@section('content')

@php
    $phoneCountries = $phoneCountries ?? \App\Models\PhoneCountry::listingQuery()->get(['id', 'iso_code', 'name', 'dial_code', 'flag_emoji']);
@endphp
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<section class="contact-hero" @if(!empty($contactHeroUrl)) style="--contact-hero-image:url('{{ $contactHeroUrl }}');" @endif>
    <div class="contact-hero__overlay"></div>
    <div class="container contact-hero__inner">
        <h1>{{ $contactPage['hero_line_1'] ?? '' }}<br>{{ $contactPage['hero_line_2'] ?? '' }}</h1>
        @if(trim((string) ($contactPage['hero_cta'] ?? '')) !== '')
        <a href="#contactFormPanel" class="hero-btn hero-btn--gold home-atelier-btn">{{ $contactPage['hero_cta'] }} <i class="fas fa-arrow-up-right"></i></a>
        @endif
    </div>
</section>

<section class="contact-main">
    <div class="container">
        <div class="row g-4 g-xl-5 align-items-start">
            <div class="col-lg-6">
                <div class="contact-main__info">
                    <span class="finishes-intro__eyebrow">{{ $contactPage['info_eyebrow'] ?? '' }}</span>
                    <h2 class="finishes-intro__title">{{ $contactPage['info_heading_1'] ?? '' }}<br>{{ $contactPage['info_heading_2'] ?? '' }}</h2>
                    <p class="contact-main__lead">
                        {!! nl2br(e($contactPage['info_lead'] ?? '')) !!}
                    </p>

                    <div class="contact-main__block">
                        <span class="finishes-intro__eyebrow">{{ $contactPage['studio_label'] ?? '' }}</span>
                        <p class="contact-main__studio">
                            {!! nl2br(e($contactPage['studio_body'] ?? '')) !!}
                        </p>
                    </div>

                    <div class="contact-main__lines">
                        @if(\App\Support\SitePhone::hasPhone($settings))
                        <div class="contact-main__phone-line"><i class="fas fa-phone" aria-hidden="true"></i> <a href="tel:{{ \App\Support\SitePhone::telHref($settings) }}">{{ \App\Support\SitePhone::display($settings) }}</a></div>
                        @endif
                        @if(\App\Support\SitePhone::hasWhatsapp($settings))
                        <div class="contact-main__phone-line"><i class="fa-brands fa-square-whatsapp"></i> <a href="{{ \App\Support\SitePhone::whatsappHref($settings) }}" target="_blank" rel="noopener noreferrer">{{ \App\Support\SitePhone::whatsappDisplay($settings) }}</a></div>
                        @endif
                        @if(trim((string) $settings->get('site_email')) !== '')
                        <div><i class="fas fa-envelope"></i> {{ $settings->get('site_email') }}</div>
                        @endif
                        @if(trim((string) ($contactPage['appointment_line'] ?? '')) !== '')
                     <div> <i class="fa-solid fa-clock"></i> {{ $contactPage['appointment_line'] }}</div>
                        @endif
                    </div>

                    <div class="contact-main__block">
                        <span class="finishes-intro__eyebrow">{{ $contactPage['hours_label'] ?? '' }}</span>
                        <p class="contact-main__hours">{!! nl2br(e($contactPage['hours_body'] ?? '')) !!}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- <div class="contact-main__form-panel" id="contactFormPanel">
                    <div class="contact-main__form-topline" aria-hidden="true"></div>
                    <h3>{{ $contactPage['form_title'] ?? '' }}</h3>
                    <div id="contactFormAjaxFeedback" class="d-none" role="status" aria-live="polite"></div>
                    @if (session('success'))
                    <div class="alert alert-success mb-3" role="status">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        {{ $contactPage['form_error_intro'] ?? '' }}
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST"
                          data-contact-ajax="1"
                          data-feedback-id="contactFormAjaxFeedback"
                          data-error-intro="{{ e($contactPage['form_error_intro'] ?? '') }}">
                        @csrf
                        <input type="hidden" name="_form_context" value="contact">
                        <input type="hidden" name="subject" value="{{ old('subject', $contactPage['subject_default'] ?? '') }}">
                        <div class="contact-main__form-grid">
                            <input type="text" id="contact_name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="{{ $contactPage['name_placeholder'] ?? '' }}"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <input type="email" id="contact_email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="{{ $contactPage['email_placeholder'] ?? '' }}"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <div class="contact-main__field contact-main__field--phone">
                                <label class="form-label small text-muted mb-1" for="contact_page_phone_national">{{ $contactPage['phone_field_label'] ?? '' }}</label>
                                @include('partials.intl-phone-field', [
                                    'countries' => $phoneCountries,
                                    'mode' => 'combined',
                                    'namePhone' => 'phone',
                                    'combinedPhoneValue' => old('phone'),
                                    'defaultIso' => 'GB',
                                    'instanceId' => 'contact_page_phone',
                                    'nationalPlaceholder' => $contactPage['national_placeholder'] ?? '',
                                    'nationalMaxLength' => 20,
                                    'invalid' => $errors->has('phone'),
                                ])
                                @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <textarea id="contact_message" name="message"
                                class="form-control contact-main__message @error('message') is-invalid @enderror"
                                placeholder="{{ $contactPage['message_placeholder'] ?? '' }}">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            <button type="submit" class="contact-main__submit">
                                {{ $contactPage['submit_label'] ?? '' }} <i class="fas fa-arrow-up-right"></i>
                            </button>
                        </div>
                    </form>
                </div> -->
                <div class="home-contact-panel" id="contactFormPanel" class="">
                    <h3 class="home-contact-panel__title">{{ $contactPage['form_title'] ?? '' }}</h3>
                    <div id="contactFormAjaxFeedback" class="d-none" role="status" aria-live="polite"></div>
                    @if (session('success'))
                    <div class="alert alert-success mb-3" role="status">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        {{ $contactPage['form_error_intro'] ?? '' }}
                    </div>
                    @endif
                   <form action="{{ route('contact.store') }}" method="POST"
                          data-contact-ajax="1"
                          data-feedback-id="contactFormAjaxFeedback"
                          data-error-intro="{{ e($contactPage['form_error_intro'] ?? '') }}">
                        @csrf
                        <input type="hidden" name="_form_context" value="contact">
                        <input type="hidden" name="subject" value="{{ old('subject', $contactPage['subject_default'] ?? '') }}">
                        <div class="mb-3">
                            <input type="text" id="contact_name" name="name"
                                class="form-control home-contact-input  @error('name') is-invalid @enderror"
                                placeholder="{{ $contactPage['name_placeholder'] ?? '' }}"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                             <input type="email" id="contact_email" name="email"
                                class="form-control home-contact-input @error('email') is-invalid @enderror"
                                placeholder="{{ $contactPage['email_placeholder'] ?? '' }}"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                           
                            <div class="contact-main__field contact-main__field--phone">
                                <label class="form-label small text-muted mb-1" for="contact_page_phone_national">{{ $contactPage['phone_field_label'] ?? '' }}</label>
                                @include('partials.intl-phone-field', [
                                    'countries' => $phoneCountries,
                                    'mode' => 'combined',
                                    'namePhone' => 'phone',
                                    'combinedPhoneValue' => old('phone'),
                                    'defaultIso' => 'GB',
                                    'instanceId' => 'contact_page_phone',
                                    'nationalPlaceholder' => $contactPage['national_placeholder'] ?? '',
                                    'nationalMaxLength' => 20,
                                    'invalid' => $errors->has('phone'),
                                ])
                                @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-4">
                               <textarea id="contact_message" name="message"
                                class="form-control home-contact-input contact-main__message @error('message') is-invalid @enderror"
                                placeholder="{{ $contactPage['message_placeholder'] ?? '' }}">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        
                         <button type="submit" class="home-contact-submit w-100">
                                {{ $contactPage['submit_label'] ?? '' }} <i class="fas fa-arrow-up-right"></i>
                            </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty($contactPage['show_map']) && !empty(trim((string)($contactPage['map_embed_url'] ?? ''))))
<section class="contact-map-wrap" aria-label="Map">
    <iframe
        src="{{ $contactPage['map_embed_url'] }}"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen=""
    ></iframe>
</section>
@endif

@endsection
