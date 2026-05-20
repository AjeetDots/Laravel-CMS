@php
    $enc = old('mail_smtp_encryption', $settings->get('mail_smtp_encryption'));
    if ($enc === null) {
        $enc = '';
    }
@endphp
<div class="mb-0">
    <h2 class="h5 mb-2">SMTP</h2>
    <p class="text-muted small mb-4">When <strong>SMTP host</strong> is filled in, the site sends mail (contact form, newsletter notices, templates) through this server instead of the values in your <code>.env</code> file. Leave the host empty to keep using the server configuration only.</p>

    <div class="row g-3">
        <div class="col-12 col-md-8">
            <label class="form-label" for="mail_smtp_host">SMTP host</label>
            <input type="text" name="mail_smtp_host" id="mail_smtp_host" class="form-control @error('mail_smtp_host') is-invalid @enderror"
                   value="{{ old('mail_smtp_host', $settings->get('mail_smtp_host')) }}"
                   placeholder="e.g. smtp.mailtrap.io" autocomplete="off">
            @error('mail_smtp_host')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label" for="mail_smtp_port">Port</label>
            <input type="number" name="mail_smtp_port" id="mail_smtp_port" class="form-control @error('mail_smtp_port') is-invalid @enderror"
                   value="{{ old('mail_smtp_port', $settings->get('mail_smtp_port')) }}"
                   placeholder="587" min="1" max="65535" autocomplete="off">
            @error('mail_smtp_port')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="mail_smtp_username">Username</label>
            <input type="text" name="mail_smtp_username" id="mail_smtp_username" class="form-control @error('mail_smtp_username') is-invalid @enderror"
                   value="{{ old('mail_smtp_username', $settings->get('mail_smtp_username')) }}"
                   placeholder="SMTP username" autocomplete="username">
            @error('mail_smtp_username')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="mail_smtp_password">Password</label>
            <input type="password" name="mail_smtp_password" id="mail_smtp_password" class="form-control @error('mail_smtp_password') is-invalid @enderror"
                   value=""
                   placeholder="{{ $settings->get('mail_smtp_password') ? 'Leave blank to keep the current password' : 'Optional' }}"
                   autocomplete="new-password">
            @error('mail_smtp_password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <p class="form-text small mb-0">Leave blank to keep the saved password. Stored encrypted on the server.</p>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="mail_smtp_encryption">Encryption</label>
            <select name="mail_smtp_encryption" id="mail_smtp_encryption" class="form-select @error('mail_smtp_encryption') is-invalid @enderror">
                <option value="" @selected($enc === '')>None</option>
                <option value="tls" @selected($enc === 'tls')>TLS</option>
                <option value="ssl" @selected($enc === 'ssl')>SSL</option>
            </select>
            @error('mail_smtp_encryption')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <hr class="my-4">

    <h3 class="h6 fw-semibold mb-3">Envelope “From” (optional)</h3>
    <p class="text-muted small mb-3">If empty, the application uses <code>MAIL_FROM_ADDRESS</code> / <code>MAIL_FROM_NAME</code> from <code>.env</code>, or falls back to your site name and General tab email where the code supplies defaults.</p>
    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label class="form-label" for="mail_from_address">From email</label>
            <input type="email" name="mail_from_address" id="mail_from_address" class="form-control @error('mail_from_address') is-invalid @enderror"
                   value="{{ old('mail_from_address', $settings->get('mail_from_address')) }}"
                   placeholder="e.g. noreply@yourdomain.com" autocomplete="email">
            @error('mail_from_address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="mail_from_name">From name</label>
            <input type="text" name="mail_from_name" id="mail_from_name" class="form-control @error('mail_from_name') is-invalid @enderror"
                   value="{{ old('mail_from_name', $settings->get('mail_from_name')) }}"
                   placeholder="e.g. Bespoke Ornate Plaster" autocomplete="organization">
            @error('mail_from_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
