{{-- POST /admin/maintenance/cache-purge. Feedback is via admin toasts after redirect (no browser confirm). --}}
@php
    $buttonClass = $buttonClass ?? 'btn btn-sm btn-outline-secondary';
    $buttonLabel = $buttonLabel ?? 'Refresh site caches';
    $cachePurgeAction = Route::has('admin.maintenance.cache-purge')
        ? route('admin.maintenance.cache-purge')
        : url('/admin/maintenance/cache-purge');
@endphp
<form action="{{ $cachePurgeAction }}" method="POST" class="d-inline-flex align-items-center js-admin-cache-purge-form">
    @csrf
    <button type="submit" class="{{ $buttonClass }}" title="Use after saving content so the live website shows your latest changes.">
        <i class="fas fa-sync-alt me-1" aria-hidden="true"></i><span class="js-admin-cache-purge-label">{{ $buttonLabel }}</span>
    </button>
</form>
@once
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.js-admin-cache-purge-form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('button[type="submit"]');
                var label = form.querySelector('.js-admin-cache-purge-label');
                if (!btn || btn.disabled) return;
                btn.disabled = true;
                if (label) {
                    label.textContent = 'Refreshing…';
                }
            });
        });
    });
    </script>
    @endpush
@endonce
