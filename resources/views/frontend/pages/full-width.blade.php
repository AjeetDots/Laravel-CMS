@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
    <div class="cms-page-layout cms-page-layout--full">
        @include('frontend.pages.partials.cms-hero', ['page' => $page])

        <div class="cms-builder">
            <div class="container cms-page-container cms-builder__shell">
                @include('frontend.pages.partials.cms-body-inner', ['page' => $page, 'layout' => 'full'])
            </div>
        </div>
    </div>
@endsection
