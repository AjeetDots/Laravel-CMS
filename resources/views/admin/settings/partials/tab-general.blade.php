<div class="mb-0">
    <h2 class="h5 mb-2">General</h2>
    <p class="text-muted small mb-4">Site name, contact details, and footer text shown on the public site.</p>

    <div class="row g-3 mb-1">
        <div class="col-md-6">
            <label class="form-label" for="site_name">Site name <span class="text-danger">*</span></label>
            <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings->get('site_name')) }}" required maxlength="100" autocomplete="organization">
            @error('site_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="site_tagline">Tagline</label>
            <input type="text" name="site_tagline" id="site_tagline" class="form-control @error('site_tagline') is-invalid @enderror" value="{{ old('site_tagline', $settings->get('site_tagline')) }}" maxlength="200">
            @error('site_tagline')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            @php
                $siteEmailHelp = 'Shown on the website and used as the default "from" context where appropriate.';
            @endphp
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <label class="form-label mb-0" for="site_email">Email</label>
                <button type="button"
                        class="btn btn-link text-info p-0 border-0 lh-1 settings-field-help-icon"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-custom-class="settings-field-tooltip"
                        data-bs-title="{{ e($siteEmailHelp) }}"
                        aria-label="{{ e($siteEmailHelp) }}">
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                </button>
            </div>
            <input type="email" name="site_email" id="site_email" class="form-control @error('site_email') is-invalid @enderror" value="{{ old('site_email', $settings->get('site_email')) }}" autocomplete="email">
            @error('site_email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="admin_site_phone_national">Phone</label>
            @include('partials.intl-phone-field', [
                'countries' => $phoneCountries ?? collect(),
                'mode' => 'split',
                'nameCountryId' => 'site_phone_country_id',
                'nameNational' => 'site_phone_national',
                'selectedCountryId' => $settings->get('site_phone_country_id'),
                'nationalValue' => $settings->get('site_phone_national'),
                'defaultIso' => 'GB',
                'instanceId' => 'admin_site_phone',
                'nationalPlaceholder' => 'National number',
                'invalid' => $errors->has('site_phone_country_id') || $errors->has('site_phone_national'),
            ])
            @error('site_phone_country_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('site_phone_national')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @if(($phoneCountries ?? collect())->isEmpty())
                <p class="form-text text-warning mb-0 small">No countries in the database yet. Run <code>php artisan migrate</code> and <code>php artisan db:seed --class=PhoneCountrySeeder</code>.</p>
            @else
                <p class="form-text mb-0 small">Shown on the site as: <strong>{{ \App\Support\SitePhone::display($settings) ?: '—' }}</strong></p>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="site_address">Address</label>
            <textarea name="site_address" id="site_address" class="form-control @error('site_address') is-invalid @enderror" rows="3">{{ old('site_address', $settings->get('site_address')) }}</textarea>
            @error('site_address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="footer_about">Footer about text</label>
            <textarea name="footer_about" id="footer_about" class="form-control @error('footer_about') is-invalid @enderror" rows="4">{{ old('footer_about', $settings->get('footer_about')) }}</textarea>
            @error('footer_about')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="copyright_text">Copyright text</label>
            <input type="text" name="copyright_text" id="copyright_text" class="form-control @error('copyright_text') is-invalid @enderror"
                   value="{{ old('copyright_text', $settings->get('copyright_text')) }}"
                   placeholder="© {{ date('Y') }} {{ $settings->get('site_name','Bespoke Ornate Plaster') }}. All rights reserved.">
            <div class="form-text">Leave blank to auto-generate from the site name.</div>
            @error('copyright_text')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
