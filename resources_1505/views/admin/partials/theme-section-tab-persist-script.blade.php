{{-- Expects: $tabListId (string), $inputId (string). Tab triggers use data-theme-section. --}}
@php
    $tabListId = $tabListId ?? '';
    $inputId = $inputId ?? '';
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabList = document.getElementById({!! json_encode($tabListId) !!});
    var input = document.getElementById({!! json_encode($inputId) !!});
    var form = input ? input.closest('form') : null;
    if (!tabList || !input || !form) {
        return;
    }
    function syncActiveSection() {
        var active = tabList.querySelector('.nav-link.active');
        var key = active && active.getAttribute ? active.getAttribute('data-theme-section') : null;
        if (key) {
            input.value = key;
        }
    }
    tabList.addEventListener('shown.bs.tab', syncActiveSection);
    form.addEventListener('submit', syncActiveSection);

    var desired = (input.value || '').trim();
    if (desired && window.bootstrap && bootstrap.Tab) {
        var trigger = tabList.querySelector('[data-theme-section="' + CSS.escape(desired) + '"]');
        if (trigger && !trigger.classList.contains('active')) {
            bootstrap.Tab.getOrCreateInstance(trigger).show();
        }
    }
});
</script>
