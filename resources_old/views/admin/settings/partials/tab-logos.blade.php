<div class="mb-0">
    <h2 class="h5 mb-3">Site logos</h2>
    <p class="text-muted small mb-4">Header, admin sidebar, footer, and favicon. Use Refresh site caches in the top bar after saving if the live site still shows old images.</p>

    <div class="mb-4">
        <label class="form-label fw-semibold" for="site_logo">Header logo <small class="text-muted fw-normal">(light background)</small></label>
        @if($settings->get('site_logo'))
            <div class="mb-2 p-3 border rounded" style="background:#f8f9fa;">
                <img src="{{ asset('storage/' . $settings->get('site_logo')) }}" alt="Header logo" style="max-height:50px; max-width:200px;">
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="remove_site_logo" value="1" id="remove_site_logo">
                <label class="form-check-label text-danger" for="remove_site_logo">Remove current logo</label>
            </div>
        @endif
        <input type="file" name="site_logo" id="site_logo" class="form-control @error('site_logo') is-invalid @enderror"
               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
        <div class="form-text">PNG, WebP, or SVG with transparent background recommended. Max 2MB.</div>
        @error('site_logo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold" for="backend_logo">Backend logo <small class="text-muted fw-normal">(admin sidebar)</small></label>
        @if($settings->get('backend_logo'))
            <div class="mb-2 p-3 border rounded" style="background:#f8f9fa;">
                <img src="{{ asset('storage/' . $settings->get('backend_logo')) }}" alt="Backend logo" style="max-height:50px; max-width:200px;">
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="remove_backend_logo" value="1" id="remove_backend_logo">
                <label class="form-check-label text-danger" for="remove_backend_logo">Remove backend logo</label>
            </div>
        @else
            <div class="alert alert-info py-2 mb-2" style="font-size:.82rem;">If not set, the admin panel uses the default text label.</div>
        @endif
        <input type="file" name="backend_logo" id="backend_logo" class="form-control @error('backend_logo') is-invalid @enderror"
               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
        <div class="form-text">Used only in the admin sidebar. Max 2MB.</div>
        @error('backend_logo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold" for="site_logo_footer">Footer logo <small class="text-muted fw-normal">(dark background)</small></label>
        @if($settings->get('site_logo_footer'))
            <div class="mb-2 p-3 border rounded" style="background:#1a1a18;">
                <img src="{{ asset('storage/' . $settings->get('site_logo_footer')) }}" alt="Footer logo" style="max-height:50px; max-width:200px;">
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="remove_site_logo_footer" value="1" id="remove_site_logo_footer">
                <label class="form-check-label text-danger" for="remove_site_logo_footer">Remove current footer logo</label>
            </div>
        @else
            <div class="alert alert-info py-2 mb-2" style="font-size:.82rem;">If not set, the header logo is used in the footer.</div>
        @endif
        <input type="file" name="site_logo_footer" id="site_logo_footer" class="form-control @error('site_logo_footer') is-invalid @enderror"
               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
        <div class="form-text">Light artwork for the dark footer. Max 2MB.</div>
        @error('site_logo_footer')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label class="form-label fw-semibold" for="site_favicon">Favicon</label>
        @if($settings->get('site_favicon'))
            <div class="mb-2 d-flex align-items-center gap-3 p-3 border rounded" style="background:#f8f9fa;">
                <img src="{{ asset('storage/' . $settings->get('site_favicon')) }}" alt="Favicon"
                     style="width:32px;height:32px;object-fit:contain;image-rendering:pixelated;">
                <span class="text-muted" style="font-size:.82rem;">Current favicon</span>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="remove_site_favicon" value="1" id="remove_site_favicon">
                <label class="form-check-label text-danger" for="remove_site_favicon">Remove current favicon</label>
            </div>
        @endif
        <input type="file" name="site_favicon" id="site_favicon" class="form-control @error('site_favicon') is-invalid @enderror"
               accept=".ico,.png,.svg,.jpg,.jpeg,.gif,.webp,image/x-icon,image/png,image/svg+xml,image/webp">
        <div id="faviconPreview" class="mt-2" style="display:none;">
            <img id="faviconPreviewImg" src="" alt="Preview"
                 style="width:32px;height:32px;object-fit:contain;image-rendering:pixelated;border:1px solid #e2e8f0;border-radius:4px;">
            <span class="text-muted ms-2" style="font-size:.8rem;">Preview</span>
        </div>
        <div class="form-text">ICO, PNG or SVG. Recommended 32×32 or 64×64 px. Max 512KB.</div>
        @error('site_favicon')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>
