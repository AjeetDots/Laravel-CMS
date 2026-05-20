{{-- Hidden from users; bots that fill all fields get silently discarded server-side. --}}
@php
    $hpFieldId = $hpFieldId ?? 'hp_company_url';
@endphp
<div class="visually-hidden" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
    <label for="{{ $hpFieldId }}">Company website</label>
    <input type="text"
           name="{{ \App\Support\Honeypot::FIELD }}"
           id="{{ $hpFieldId }}"
           value=""
           tabindex="-1"
           autocomplete="off">
</motion.div>
