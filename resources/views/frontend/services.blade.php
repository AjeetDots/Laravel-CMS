@extends('layouts.frontend')
@section('title', 'Services')
@section('body_class', 'nav-solid page-services')

@section('styles')
@php
    $__homeCss = public_path('css/home.css');
    $__homeCssV = is_file($__homeCss) ? filemtime($__homeCss) : time();
@endphp
<link href="{{ asset('css/home.css') }}?v={{ $__homeCssV }}" rel="stylesheet">
@endsection

@section('content')

<section class="svc-intro">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <span class="svc-intro__eyebrow">{{ $servicesPage['intro_eyebrow'] }}</span>
                <h1 class="svc-intro__title">{!! nl2br(e($servicesPage['intro_title'])) !!}</h1>
                <p class="svc-intro__desc">{{ $servicesPage['intro_body'] }}</p>
            </div>
        </div>
    </div>
</section>

@forelse($services as $service)
<section class="svc-split {{ $loop->even ? 'svc-split--flip' : '' }}">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-center">
            <div class="col-lg-6 {{ $loop->even ? 'order-lg-2' : '' }}">
                <div class="svc-split__img-wrap">
                    @if($service->image)
                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}" loading="lazy" decoding="async">
                    @else
                        <div class="svc-split__placeholder">
                            <i class="{{ $service->icon ?? 'fas fa-paint-brush' }}"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 {{ $loop->even ? 'order-lg-1' : '' }}">
                <div class="svc-split__body">
                    @if($service->badge)
                        <span class="svc-split__eyebrow">{{ $service->badge }}</span>
                    @endif
                    <h2 class="svc-split__title">{{ $service->title }}</h2>
                    @if($service->short_description)
                        <p class="svc-split__desc">{{ $service->short_description }}</p>
                    @endif
                    @if($service->features && count($service->features))
                        <ul class="svc-split__features">
                            @foreach($service->features as $feat)
                                @if($feat)
                                    <li>{{ $feat }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('services.show', $service->slug) }}" class="svc-split__cta">
                        {{ trim($servicesPage['service_cta_prefix']) }} {{ $service->title }}
                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                            <path d="M1 10L10 1M10 1H3M10 1V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@empty
<div class="container text-center py-5 services-empty-state">
    <i class="fas fa-concierge-bell fa-3x mb-3" aria-hidden="true"></i>
    <p class="mb-3">{{ $servicesPage['empty_message'] }}</p>
    @if(!empty(trim($servicesPage['empty_btn_text'] ?? '')))
        <a href="{{ $servicesPage['empty_btn_href'] }}" class="btn-outline-site">{{ $servicesPage['empty_btn_text'] }}</a>
    @endif
</div>
@endforelse

<div class="cta-strip">
    <div class="container text-center">
        <span class="eyebrow">{{ $servicesPage['bottom_eyebrow'] }}</span>
        <h2>{!! nl2br(e($servicesPage['bottom_heading'])) !!}</h2>
        @if(!empty(trim($servicesPage['bottom_body'] ?? '')))
            <p class="mb-4">{{ $servicesPage['bottom_body'] }}</p>
        @endif
        <a href="{{ $servicesPage['bottom_btn_href'] }}" class="btn-white">{{ $servicesPage['bottom_btn_text'] }} <i class="fas fa-arrow-right" style="font-size:.75rem;" aria-hidden="true"></i></a>
    </div>
</div>

@endsection
