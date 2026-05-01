@extends('layouts.frontend')
@section('title', $service->title)
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Our services</span>
        <h1 class="page-hero-title-wide">{{ $service->title }}</h1>
        <p>{{ $service->short_description }}</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
                <li class="breadcrumb-item active">{{ $service->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($service->image)
                <div class="service-detail-lead-img">
                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}">
                </div>
                @endif
                <div class="service-detail-body">
                    {!! $service->description ?? '<p>'.$service->short_description.'</p>' !!}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="service-sidebar-wrap">
                    <div class="service-sidebar-card">
                        <span class="eyebrow">Ready to start?</span>
                        <h4>Let's work together</h4>
                        <p class="sub">Tell us about your project and we'll get back to you within 24 hours.</p>
                        <a href="{{ route('contact') }}" class="btn-primary-site w-100 justify-content-center mb-3">
                            Get in touch <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                        </a>
                        <a href="{{ route('services') }}" class="btn-outline-site w-100 justify-content-center">
                            All services
                        </a>
                    </div>
                    @php $otherServices = \App\Models\Service::where('is_active',true)->where('slug','!=',$service->slug)->limit(4)->get(); @endphp
                    @if($otherServices->count())
                    <div class="mt-4">
                        <p class="service-sidebar-more-label">Other services</p>
                        @foreach($otherServices as $other)
                        <a href="{{ route('services.show',$other->slug) }}" class="service-sidebar-link text-decoration-none">
                            <i class="{{ $other->icon ?? 'fas fa-cog' }}"></i>
                            {{ $other->title }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
