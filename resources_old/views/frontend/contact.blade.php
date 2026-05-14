@extends('layouts.frontend')
@section('title', trim((string) ($contactPage['page_title'] ?? '')) !== '' ? $contactPage['page_title'] : '')
@section('body_class', 'nav-solid page-contact')
@section('content')
@include('frontend.partials.contact-page-sections', [
    'phoneCountries' => $phoneCountries,
    'contactPage' => $contactPage,
    'contactHeroUrl' => $contactHeroUrl,
])
@endsection
