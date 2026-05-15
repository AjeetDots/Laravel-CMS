@php
    $siteToasts = [];
    if (session()->has('success')) {
        $siteToasts[] = ['type' => 'success', 'message' => session('success')];
    }
    if (session()->has('error')) {
        $siteToasts[] = ['type' => 'danger', 'message' => session('error')];
    }
    if (session()->has('warning')) {
        $siteToasts[] = ['type' => 'warning', 'message' => session('warning')];
    }
    if (session()->has('newsletter_success')) {
        $siteToasts[] = ['type' => 'success', 'message' => session('newsletter_success')];
    }
    if (session()->has('status')) {
        $siteToasts[] = ['type' => 'success', 'message' => session('status')];
    }
    if (isset($errors) && $errors->any()) {
        $siteToasts[] = ['type' => 'danger', 'message' => $errors->first()];
    }
@endphp

@if(count($siteToasts))
<div id="siteToastStack" class="site-toast-stack" aria-live="polite">
    @foreach($siteToasts as $i => $toast)
        @php
            $variant = $toast['type'];
            $toastIcons = [
                'success' => 'fa-check-circle',
                'warning' => 'fa-exclamation-triangle',
                'danger' => 'fa-exclamation-circle',
            ];
            $icon = $toastIcons[$variant] ?? 'fa-info-circle';
        @endphp
        <div class="site-toast site-toast--{{ $variant }}" data-site-toast role="status">
            <i class="fas {{ $icon }} site-toast__icon" aria-hidden="true"></i>
            <span class="site-toast__text">{{ $toast['message'] }}</span>
            <button type="button" class="site-toast__close" aria-label="Dismiss notification">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    @endforeach
</div>
<script>
(function () {
    var stack = document.getElementById('siteToastStack');
    if (!stack) return;
    var AUTO_MS = 5500;
    function dismissToast(el) {
        if (!el || el.classList.contains('site-toast--out')) return;
        el.classList.add('site-toast--out');
        window.setTimeout(function () {
            el.remove();
            if (stack && stack.childElementCount === 0) stack.remove();
        }, 320);
    }
    stack.querySelectorAll('[data-site-toast]').forEach(function (toast) {
        var btn = toast.querySelector('.site-toast__close');
        var timer = window.setTimeout(function () { dismissToast(toast); }, AUTO_MS);
        if (btn) {
            btn.addEventListener('click', function () {
                window.clearTimeout(timer);
                dismissToast(toast);
            });
        }
    });
})();
</script>
@endif
