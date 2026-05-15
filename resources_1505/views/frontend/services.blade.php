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
                <span class="finishes-intro__eyebrow">{{ $servicesPage['intro_eyebrow'] }}</span>
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
                            @if(trim((string) ($service->icon ?? '')) !== '')
                            <i class="{{ $service->icon }}"></i>
                            @endif
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
                    <div class="heritage-list">
                        <ul>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                Hand-cast in studio
                            </li>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                Bespoke profile design
                            </li>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                Heritage replication
                            </li>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                Sympathetic restoration
                            </li>
                        </ul>
                    </div>
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
                        @if(trim((string) ($servicesPage['service_cta_prefix'] ?? '')) !== '')
                        {{ trim($servicesPage['service_cta_prefix']) }} {{ $service->title }}
                        @else
                        {{ $service->title }}
                        @endif
                       <i class="fas fa-arrow-right"></i>
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
    @if(!empty(trim($servicesPage['empty_btn_text'] ?? '')) && !empty(trim($servicesPage['empty_btn_href'] ?? '')))
        <a href="{{ $servicesPage['empty_btn_href'] }}" class="btn-outline-site">{{ $servicesPage['empty_btn_text'] }}</a>
    @endif
</div>
@endforelse

<div class="cta-strip">
    <div class="container text-center">
        <span class="finishes-intro__eyebrow">{{ $servicesPage['bottom_eyebrow'] }}</span>
        <h2 class="svc-split__title">{!! nl2br(e($servicesPage['bottom_heading'])) !!}</h2>
        @if(!empty(trim($servicesPage['bottom_body'] ?? '')))
            <p class="mb-4">{{ $servicesPage['bottom_body'] }}</p>
        @endif
        @if(!empty(trim($servicesPage['bottom_btn_text'] ?? '')) && !empty(trim($servicesPage['bottom_btn_href'] ?? '')))
        <a href="{{ $servicesPage['bottom_btn_href'] }}" class="hero-btn hero-btn--gold home-atelier-btn">{{ $servicesPage['bottom_btn_text'] }} <i class="fas fa-arrow-right rotate-45"  aria-hidden="true"></i></a>
        @endif
    </div>
</div>

@endsection
