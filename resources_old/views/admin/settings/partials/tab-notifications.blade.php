<div class="mb-0">
    <h2 class="h5 mb-2">Notifications</h2>
    <p class="text-muted small mb-4">Choose where internal copies of visitor activity are sent. Your public contact email stays on the General tab.</p>

    <div class="row g-3 align-items-start">
        <div class="col-12 col-md-6">
            @php
                $adminNotifyHelp = 'Used for system-generated messages to your team. Leave empty to use the main site email from the General tab instead.';
            @endphp
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <label class="form-label mb-0" for="admin_notification_email">Admin notification email</label>
                <button type="button"
                        class="btn btn-link text-info p-0 border-0 lh-1 settings-field-help-icon"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-custom-class="settings-field-tooltip"
                        data-bs-title="{{ e($adminNotifyHelp) }}"
                        aria-label="{{ e($adminNotifyHelp) }}">
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                </button>
            </div>
            <input type="email" name="admin_notification_email" id="admin_notification_email" class="form-control @error('admin_notification_email') is-invalid @enderror"
                   value="{{ old('admin_notification_email', $settings->get('admin_notification_email')) }}"
                   placeholder="e.g. office@yourstudio.com" autocomplete="email">
            @error('admin_notification_email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6">
            <div class="border rounded-3 p-3 h-100" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                <h3 class="h6 fw-semibold text-dark mb-3">What this inbox receives</h3>
                <ul class="small text-secondary mb-3 ps-3">
                    <li class="mb-2"><strong class="text-dark">Contact form</strong> — when someone submits an enquiry, a copy can be sent here so staff see it quickly.</li>
                    <li class="mb-0"><strong class="text-dark">Newsletter</strong> — optional admin notice when a new subscriber joins your list.</li>
                </ul>
                <p class="small text-muted mb-0">Email templates under <strong>Communication</strong> use this address when configured. If both this field and the General tab email are empty, those notifications are skipped.</p>
            </div>
        </div>
    </div>
</div>
