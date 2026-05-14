@extends('layouts.admin')

@section('title', 'Footer newsletter')

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Footer newsletter</h1>
        <p class="text-muted mb-0 small">Copy for the signup block in the site footer. Subscribers are still managed under <a href="{{ route('admin.newsletter.index') }}">Newsletter</a>.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'newsletter_footer'])

<form action="{{ route('admin.theme-options.newsletter-footer.update') }}" method="POST">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <span class="fw-600">Form &amp; messages</span>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label" for="heading">Heading</label>
                <input type="text" name="heading" id="heading" class="form-control" value="{{ old('heading', $data['heading'] ?? '') }}" maxlength="120">
            </div>
            <div class="mb-3">
                <label class="form-label" for="lead">Intro line</label>
                <textarea name="lead" id="lead" class="form-control" rows="2" maxlength="500">{{ old('lead', $data['lead'] ?? '') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="email_label">Email field label <span class="text-muted fw-normal small">(screen readers)</span></label>
                    <input type="text" name="email_label" id="email_label" class="form-control" value="{{ old('email_label', $data['email_label'] ?? '') }}" maxlength="120">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="placeholder">Email placeholder</label>
                    <input type="text" name="placeholder" id="placeholder" class="form-control" value="{{ old('placeholder', $data['placeholder'] ?? '') }}" maxlength="120">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="submit_label">Button label</label>
                    <input type="text" name="submit_label" id="submit_label" class="form-control" value="{{ old('submit_label', $data['submit_label'] ?? '') }}" maxlength="80">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="submit_busy_label">Button text while sending</label>
                    <input type="text" name="submit_busy_label" id="submit_busy_label" class="form-control" value="{{ old('submit_busy_label', $data['submit_busy_label'] ?? '') }}" maxlength="40" placeholder="e.g. Sending…">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="privacy_text">Privacy line <span class="text-muted fw-normal small">(after the lock icon)</span></label>
                <input type="text" name="privacy_text" id="privacy_text" class="form-control" value="{{ old('privacy_text', $data['privacy_text'] ?? '') }}" maxlength="255">
            </div>
            <hr class="my-4">
            <p class="text-muted small mb-3">These should match what visitors see after submit (JSON responses and optional flash message).</p>
            <div class="mb-3">
                <label class="form-label" for="message_success">Success message</label>
                <input type="text" name="message_success" id="message_success" class="form-control" value="{{ old('message_success', $data['message_success'] ?? '') }}" maxlength="255">
            </div>
            <div class="mb-3">
                <label class="form-label" for="message_already_subscribed">Already subscribed message</label>
                <input type="text" name="message_already_subscribed" id="message_already_subscribed" class="form-control" value="{{ old('message_already_subscribed', $data['message_already_subscribed'] ?? '') }}" maxlength="255">
            </div>
            <div class="mb-3">
                <label class="form-label" for="message_error_generic">Generic validation / error hint</label>
                <input type="text" name="message_error_generic" id="message_error_generic" class="form-control" value="{{ old('message_error_generic', $data['message_error_generic'] ?? '') }}" maxlength="255">
            </div>
            <div class="mb-0">
                <label class="form-label" for="message_error_network">Network / server error message</label>
                <input type="text" name="message_error_network" id="message_error_network" class="form-control" value="{{ old('message_error_network', $data['message_error_network'] ?? '') }}" maxlength="255">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save footer newsletter</button>
</form>
@endsection
