@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
    @include('frontend.pages.partials.cms-hero', ['page' => $page])

    <section class="section section-white cms-page-section cms-page-section--full">
        <div class="container-fluid px-3 px-sm-4 px-lg-5">
            <div class="row">
                <div class="col-12">
                    <div class="cms-page-inner-wide">
                        @include('frontend.pages.partials.cms-body-inner', ['page' => $page])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
