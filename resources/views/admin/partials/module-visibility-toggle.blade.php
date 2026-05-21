@php
    use App\Support\CmsModuleRegistry;
    use App\Support\CmsModuleVisibility;

    $moduleKey = $module ?? '';
    $definitions = CmsModuleRegistry::definitions();
    $def = $definitions[$moduleKey] ?? null;
@endphp
@if($def)
    @php $isEnabled = CmsModuleVisibility::isEnabled($moduleKey); @endphp
    <div class="card mb-3 module-visibility-card border-0 shadow-sm">
        <div class="card-body py-3 px-3 px-md-4 d-flex flex-wrap align-items-center gap-3">
            <form action="{{ route('admin.modules.visibility', $moduleKey) }}" method="POST"
                  class="d-flex flex-wrap align-items-center gap-2 mb-0 js-module-visibility-form"
                  data-module="{{ $moduleKey }}">
                @csrf
                <input type="hidden" name="enabled" value="{{ $isEnabled ? '1' : '0' }}" class="js-module-enabled-value">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input module-visibility-checkbox" type="checkbox"
                           id="module_visibility_{{ $moduleKey }}"
                           {{ $isEnabled ? 'checked' : '' }}
                           aria-describedby="module_visibility_help_{{ $moduleKey }}">
                    <label class="form-check-label fw-semibold" for="module_visibility_{{ $moduleKey }}">
                        {{ $def['label'] }}
                    </label>
                </div>
                <button type="button"
                        class="btn btn-link text-info p-0 border-0 lh-1 module-visibility-help"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-custom-class="settings-field-tooltip"
                        data-bs-html="true"
                        data-bs-title="{{ e($def['tooltip']) }}<hr class='my-2'><span class='text-muted'><strong>Affects:</strong> {{ e($def['affects']) }}</span>"
                        aria-label="Help: {{ e($def['label']) }}">
                    <i class="fas fa-circle-info" aria-hidden="true"></i>
                </button>
            </form>
            <span class="small mb-0 module-visibility-status {{ $isEnabled ? 'text-success' : 'text-muted' }}"
                  id="module_visibility_status_{{ $moduleKey }}">
                @if($isEnabled)
                    <i class="fas fa-eye me-1" aria-hidden="true"></i>Visible on live site
                @else
                    <i class="fas fa-eye-slash me-1" aria-hidden="true"></i>Hidden on live site — save still keeps your items here
                @endif
            </span>
        </div>
        <p class="small text-muted mb-0 px-3 px-md-4 pb-3 d-none" id="module_visibility_help_{{ $moduleKey }}">
            {{ $def['affects'] }}
        </p>
    </div>
    @once
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.module-visibility-help[data-bs-toggle="tooltip"]').forEach(function (el) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    new bootstrap.Tooltip(el);
                }
            });

            document.querySelectorAll('.js-module-visibility-form').forEach(function (form) {
                var checkbox = form.querySelector('.module-visibility-checkbox');
                if (!checkbox) return;

                checkbox.addEventListener('change', function () {
                    var module = form.getAttribute('data-module');
                    var statusEl = document.getElementById('module_visibility_status_' + module);
                    var hidden = form.querySelector('.js-module-enabled-value');
                    if (hidden) {
                        hidden.value = checkbox.checked ? '1' : '0';
                    }
                    var fd = new FormData(form);

                    checkbox.disabled = true;

                    fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
                        .then(function (res) {
                            checkbox.disabled = false;
                            if (!res.ok || !res.j || !res.j.ok) {
                                checkbox.checked = !checkbox.checked;
                                if (hidden) {
                                    hidden.value = checkbox.checked ? '1' : '0';
                                }
                                return;
                            }
                            var on = !!res.j.enabled;
                            if (statusEl) {
                                statusEl.className = 'small mb-0 module-visibility-status ' + (on ? 'text-success' : 'text-muted');
                                statusEl.innerHTML = on
                                    ? '<i class="fas fa-eye me-1" aria-hidden="true"></i>Visible on live site'
                                    : '<i class="fas fa-eye-slash me-1" aria-hidden="true"></i>Hidden on live site — save still keeps your items here';
                            }
                        })
                        .catch(function () {
                            checkbox.disabled = false;
                            checkbox.checked = !checkbox.checked;
                            if (hidden) {
                                hidden.value = checkbox.checked ? '1' : '0';
                            }
                        });
                });
            });
        });
        </script>
        @endpush
    @endonce
@endif
