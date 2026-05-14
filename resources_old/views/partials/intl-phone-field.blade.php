{{--
  International-style phone field (intl-tel-input–like UI). Data from $countries (PhoneCountry models).
  @param \Illuminate\Support\Collection|array $countries
  @param string $mode split|combined — split: two named fields; combined: single hidden phone
  @param string|null $namePhone required when mode=combined (e.g. "phone")
--}}
@props([
    'countries',
    'mode' => 'split',
    'nameCountryId' => 'site_phone_country_id',
    'nameNational' => 'site_phone_national',
    'namePhone' => null,
    'selectedCountryId' => null,
    'nationalValue' => '',
    'combinedPhoneValue' => '',
    'defaultIso' => 'GB',
    'instanceId' => 'itl',
    'nationalPlaceholder' => null,
    'nationalMaxLength' => 24,
    'invalid' => false,
    'nationalRequired' => false,
])

@php
    $rows = collect($countries)->map(function ($c) {
        return [
            'id' => (int) $c->id,
            'iso_code' => (string) $c->iso_code,
            'name' => (string) $c->name,
            'dial_code' => (string) $c->dial_code,
            'flag_emoji' => (string) ($c->flag_emoji ?? ''),
        ];
    })->values()->all();

    $ukId = collect($countries)->firstWhere('iso_code', 'GB')?->id;

    if ($mode === 'combined') {
        $selCountry = $ukId;
        $natVal = '';
        $phoneField = $namePhone ?: 'phone';
        $phoneHiddenVal = old($phoneField, $combinedPhoneValue);
    } else {
        $selCountry = old($nameCountryId);
        if ($selCountry === null) {
            $selCountry = ($selectedCountryId !== null && $selectedCountryId !== '')
                ? $selectedCountryId
                : $ukId;
        }
        $natVal = old($nameNational, $nationalValue);
        $phoneHiddenVal = '';
    }

    $ph = $nationalPlaceholder ?? ($mode === 'combined' ? 'Phone (optional)' : 'National number');

    $displayRow = collect($rows)->firstWhere('id', (int) ($selCountry ?? 0));
    if (! $displayRow) {
        $displayRow = collect($rows)->firstWhere('iso_code', strtoupper((string) $defaultIso));
    }
    if (! $displayRow && count($rows) > 0) {
        $displayRow = $rows[0];
    }
@endphp

@if(count($rows) > 0)
@php
    $itlJsonFlags = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $itlJsonFlags |= JSON_INVALID_UTF8_SUBSTITUTE;
    }
    $itlJson = json_encode($rows, $itlJsonFlags);
    $itlB64 = base64_encode($itlJson);
@endphp
<div class="itl-phone @if($invalid) is-invalid @endif"
     id="{{ $instanceId }}_wrap"
     data-itl-phone
     data-itl-mode="{{ $mode }}"
     data-itl-default-iso="{{ e($defaultIso) }}"
     data-itl-countries-b64="{{ $itlB64 }}">
    @if($mode === 'split')
        <input type="hidden" name="{{ $nameCountryId }}" class="itl-phone__hidden-country" value="{{ $selCountry }}">
        <input type="hidden" name="{{ $nameNational }}" class="itl-phone__hidden-national" value="{{ $natVal }}">
    @else
        <input type="hidden" name="{{ $namePhone }}" class="itl-phone__hidden-phone" value="{{ $phoneHiddenVal }}">
    @endif
    <div class="itl-phone__row">
        <button type="button"
                class="itl-phone__toggle"
                aria-haspopup="listbox"
                aria-expanded="false"
                aria-label="Country and dial code"
                title="{{ ($displayRow['name'] ?? '').' '.($displayRow['dial_code'] ?? '') }}">
            <span class="itl-phone__flag" aria-hidden="true">
                <img class="itl-phone__flag-img"
                     src="{{ \App\Support\CountryFlag::svgDataUrlFor($displayRow['flag_emoji'] ?? '', $displayRow['iso_code'] ?? '') }}"
                     width="24"
                     height="16"
                     alt=""
                     loading="lazy"
                     decoding="async"
                     draggable="false">
            </span>
            <span class="itl-phone__dial">{{ $displayRow['dial_code'] ?? '' }}</span>
            <span class="itl-phone__caret" aria-hidden="true"></span>
        </button>
        <input type="tel"
               class="itl-phone__national-input form-control"
               id="{{ $instanceId }}_national"
               value="{{ preg_replace('/\D/', '', (string) $natVal) }}"
               placeholder="{{ $ph }}"
               maxlength="{{ (int) $nationalMaxLength }}"
               autocomplete="tel-national"
               inputmode="numeric"
               pattern="[0-9]*"
               @if($mode === 'split' && $nationalRequired) required @endif
        >
    </div>
    <div class="itl-phone__dropdown" hidden>
        <input type="search" class="itl-phone__search" placeholder="Search" autocomplete="off" aria-label="Search countries">
        <ul class="itl-phone__list" role="listbox"></ul>
    </div>
    {{-- JSON in a hidden textarea avoids </script> parsing bugs and script-in-form quirks. --}}
    <textarea id="{{ $instanceId }}_countries_json"
              class="itl-phone__json"
              hidden
              readonly
              tabindex="-1"
              autocomplete="off"
              aria-hidden="true">{!! $itlJson !!}</textarea>
</div>
@else
    <p class="form-text text-warning small mb-0">No phone countries configured.</p>
@endif
