@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<div class="page-header-bar">
    <h1>Site Settings</h1>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">General Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Site Name *</label>
                        <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->get('site_name')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings->get('site_tagline')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="site_email" class="form-control" value="{{ old('site_email', $settings->get('site_email')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="site_phone" class="form-control" value="{{ old('site_phone', $settings->get('site_phone')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="site_address" class="form-control" rows="3">{{ old('site_address', $settings->get('site_address')) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Footer About Text</label>
                        <textarea name="footer_about" class="form-control" rows="4">{{ old('footer_about', $settings->get('footer_about')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">

            {{-- Logo Upload --}}
            <div class="card mb-4">
                <div class="card-header">Site Logos</div>
                <div class="card-body">

                    {{-- Header Logo --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="site_logo">Header Logo <small class="text-muted">(light background)</small></label>
                        @if($settings->get('site_logo'))
                            <div class="mb-2 p-3 border rounded" style="background:#f8f9fa;">
                                <img src="{{ asset('storage/' . $settings->get('site_logo')) }}" alt="Header Logo" style="max-height:50px; max-width:200px;">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="remove_site_logo" value="1" id="remove_site_logo">
                                <label class="form-check-label text-danger" for="remove_site_logo">Remove current logo</label>
                            </div>
                        @endif
                        <input type="file" name="site_logo" id="site_logo" class="form-control" accept="image/*">
                        <div class="form-text">PNG/SVG with transparent background recommended. Max 2MB.</div>
                    </div>

                    {{-- Footer Logo --}}
                    <div class="mb-2">
                        <label class="form-label fw-semibold" for="site_logo_footer">Footer Logo <small class="text-muted">(dark background)</small></label>
                        @if($settings->get('site_logo_footer'))
                            <div class="mb-2 p-3 border rounded" style="background:#1a1a18;">
                                <img src="{{ asset('storage/' . $settings->get('site_logo_footer')) }}" alt="Footer Logo" style="max-height:50px; max-width:200px;">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="remove_site_logo_footer" value="1" id="remove_site_logo_footer">
                                <label class="form-check-label text-danger" for="remove_site_logo_footer">Remove current footer logo</label>
                            </div>
                        @else
                            <div class="alert alert-info py-2 mb-2" style="font-size:.82rem;">If not set, the header logo will be used in the footer.</div>
                        @endif
                        <input type="file" name="site_logo_footer" id="site_logo_footer" class="form-control" accept="image/*">
                        <div class="form-text">White/light version for dark footer. Max 2MB.</div>
                    </div>

                </div>
            </div>

            {{-- Social Media --}}
            <div class="card">
                <div class="card-header">Social Media Links</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-facebook-f me-2 text-primary"></i>Facebook URL</label>
                        <input type="url" name="social_facebook" class="form-control" value="{{ old('social_facebook', $settings->get('social_facebook')) }}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-twitter me-2" style="color:#1da1f2;"></i>Twitter URL</label>
                        <input type="url" name="social_twitter" class="form-control" value="{{ old('social_twitter', $settings->get('social_twitter')) }}" placeholder="https://twitter.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-linkedin-in me-2" style="color:#0077b5;"></i>LinkedIn URL</label>
                        <input type="url" name="social_linkedin" class="form-control" value="{{ old('social_linkedin', $settings->get('social_linkedin')) }}" placeholder="https://linkedin.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-instagram me-2" style="color:#e1306c;"></i>Instagram URL</label>
                        <input type="url" name="social_instagram" class="form-control" value="{{ old('social_instagram', $settings->get('social_instagram')) }}" placeholder="https://instagram.com/...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save me-2"></i>Save Settings
        </button>
    </div>
</form>

@endsection
