@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid page-contact')

@section('content')
@include('frontend.partials.contact-page-sections', [
    'phoneCountries' => $phoneCountries,
    'contactPage' => $contactPage,
    'contactHeroUrl' => $contactHeroUrl,
])
@endsection
