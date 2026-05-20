@php
    $adminToasts = [];
    $flashMap = [
        ['key' => 'success', 'variant' => 'success'],
        ['key' => 'error', 'variant' => 'danger'],
        ['key' => 'warning', 'variant' => 'warning'],
        ['key' => 'info', 'variant' => 'info'],
        ['key' => 'status', 'variant' => 'success'],
    ];
    foreach ($flashMap as $row) {
        if (session()->has($row['key'])) {
            $adminToasts[] = ['variant' => $row['variant'], 'message' => session($row['key'])];
        }
    }
@endphp

@if(count($adminToasts))
<div id="adminToastStack" class="admin-toast-stack" aria-live="polite" aria-relevant="additions">
    @foreach($adminToasts as $toast)
        @php
            $variant = $toast['variant'];
            $toastIcons = [
                'success' => 'fa-check-circle',
                'warning' => 'fa-exclamation-triangle',
                'danger' => 'fa-exclamation-circle',
                'info' => 'fa-info-circle',
            ];
            $icon = $toastIcons[$variant] ?? 'fa-info-circle';
        @endphp
        <div class="admin-toast admin-toast--{{ $variant }}" data-admin-toast role="status">
            <i class="fas {{ $icon }} admin-toast__icon" aria-hidden="true"></i>
            <span class="admin-toast__text">{{ $toast['message'] }}</span>
            <button type="button" class="admin-toast__close" aria-label="Dismiss notification">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    @endforeach
</div>
<script>
(function () {
    var stack = document.getElementById('adminToastStack');
    if (!stack) return;
    var AUTO_MS = 6500;
    function dismissToast(el) {
        if (!el || el.classList.contains('admin-toast--out')) return;
        el.classList.add('admin-toast--out');
        window.setTimeout(function () {
            el.remove();
            if (stack && stack.childElementCount === 0) stack.remove();
        }, 320);
    }
    stack.querySelectorAll('[data-admin-toast]').forEach(function (toast) {
        var btn = toast.querySelector('.admin-toast__close');
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
