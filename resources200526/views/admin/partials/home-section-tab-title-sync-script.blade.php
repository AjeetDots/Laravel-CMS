{{-- Syncs home page section tab labels from the per-section Title field (kicker/eyebrow inputs). Expects $tabListId. --}}
@php
    $tabListId = $tabListId ?? 'homeSectionsTabs';
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabList = document.getElementById({!! json_encode($tabListId) !!});
    if (!tabList) {
        return;
    }
    var form = tabList.closest('form');
    if (!form) {
        return;
    }
    function syncFromInput(input) {
        var key = input.getAttribute('data-sync-home-section-tab');
        if (!key) {
            return;
        }
        var btn = tabList.querySelector('[data-theme-section="' + String(key).replace(/\\/g, '\\\\').replace(/"/g, '\\"') + '"]');
        if (!btn) {
            return;
        }
        var span = btn.querySelector('.js-home-section-tab-label');
        if (!span) {
            return;
        }
        var def = btn.getAttribute('data-home-tab-default') || '';
        var v = (input.value || '').trim();
        span.textContent = v || def;
    }
    form.querySelectorAll('[data-sync-home-section-tab]').forEach(function (input) {
        syncFromInput(input);
        input.addEventListener('input', function () {
            syncFromInput(input);
        });
    });
});
</script>
