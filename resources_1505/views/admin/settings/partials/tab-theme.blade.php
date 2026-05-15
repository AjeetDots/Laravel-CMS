@php
    $defWine = '#C96B3F';
    $defWineDark = '#A8502A';
    $defGold = '#B8925A';
    $wine = old('theme_wine', $settings->get('theme_wine')) ?: $defWine;
    $wineDark = old('theme_wine_dark', $settings->get('theme_wine_dark')) ?: $defWineDark;
    $gold = old('theme_gold', $settings->get('theme_gold')) ?: $defGold;
@endphp
<div class="mb-0">
    <h2 class="h5 mb-2">Theme</h2>
    <p class="text-muted small mb-4">These colours override the public site’s accent palette (buttons, hovers, gold trim, gradients). They do not change the admin panel styling.</p>

    <div class="row g-3 align-items-end">
        <div class="col-6 col-md-4 col-lg-3">
            <label class="form-label" for="theme_wine">Primary accent</label>
            <input type="color" name="theme_wine" id="theme_wine" class="form-control form-control-color w-100 @error('theme_wine') is-invalid @enderror" value="{{ $wine }}" title="Primary accent">
            @error('theme_wine')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <label class="form-label" for="theme_wine_dark">Dark accent</label>
            <input type="color" name="theme_wine_dark" id="theme_wine_dark" class="form-control form-control-color w-100 @error('theme_wine_dark') is-invalid @enderror" value="{{ $wineDark }}" title="Dark accent">
            @error('theme_wine_dark')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <label class="form-label" for="theme_gold">Gold trim</label>
            <input type="color" name="theme_gold" id="theme_gold" class="form-control form-control-color w-100 @error('theme_gold') is-invalid @enderror" value="{{ $gold }}" title="Gold trim">
            @error('theme_gold')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mt-4 pt-2 border-top">
        <input type="hidden" name="theme_use_defaults" value="0">
        <div class="form-check">
            <input type="checkbox" name="theme_use_defaults" id="theme_use_defaults" class="form-check-input" value="1" @checked(old('theme_use_defaults') == '1')>
            <label class="form-check-label" for="theme_use_defaults">Use built-in palette (clear custom colours)</label>
        </div>
        <p class="form-text small mb-0">Check this and save to remove stored theme overrides so the stylesheet defaults apply again.</p>
    </div>
</div>
