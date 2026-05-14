{{-- Single newsletter signup (footer only — avoid duplicating with CTA). Copy: Content Hub → Footer newsletter. --}}
@php
    $nf = $newsletterFooter ?? \App\Models\NewsletterFooterContent::viewDataWithDefaults();
@endphp
<div class="footer-newsletter" id="footer-newsletter">
    <h6 class="footer-col-title" id="footer-newsletter-heading">{{ $nf['heading'] }}</h6>
    <p class="footer-newsletter__lead">{{ $nf['lead'] }}</p>
    <form action="{{ route('newsletter.subscribe') }}"
          method="POST"
          class="footer-newsletter-form"
          aria-labelledby="footer-newsletter-heading"
          data-newsletter-ajax="1"
          data-newsletter-msg-success="{{ e($nf['message_success']) }}"
          data-newsletter-msg-error-generic="{{ e($nf['message_error_generic']) }}"
          data-newsletter-msg-error-network="{{ e($nf['message_error_network']) }}"
          data-newsletter-submit-busy="{{ e($nf['submit_busy_label']) }}">
        @csrf
        <label class="visually-hidden" for="footer-newsletter-email">{{ $nf['email_label'] }}</label>
        <input type="email"
               id="footer-newsletter-email"
               name="email"
               value="{{ old('email') }}"
               placeholder="{{ $nf['placeholder'] }}"
               class="footer-newsletter-input"
               required
               autocomplete="email">
        @error('email')
            <span class="footer-newsletter-error" role="alert">{{ $message }}</span>
        @enderror
        <button type="submit" class="footer-newsletter-btn">{{ $nf['submit_label'] }}</button>
    </form>
    <p class="footer-newsletter-status" id="footer-newsletter-status" role="status" aria-live="polite" hidden></p>
    <p class="footer-newsletter-privacy">
        <i class="fas fa-lock" aria-hidden="true"></i> {{ $nf['privacy_text'] }}
    </p>
</div>
<script>
(function () {
    var form = document.querySelector('.footer-newsletter-form[data-newsletter-ajax="1"]');
    if (!form || form.getAttribute('data-newsletter-ajax-bound') === '1') return;
    form.setAttribute('data-newsletter-ajax-bound', '1');

    var statusEl = document.getElementById('footer-newsletter-status');
    var tokenInput = form.querySelector('input[name="_token"]');
    var submitBtn = form.querySelector('.footer-newsletter-btn');

    var msgSuccess = form.getAttribute('data-newsletter-msg-success') || 'Thank you for subscribing!';
    var msgErrorGeneric = form.getAttribute('data-newsletter-msg-error-generic') || 'Please check your email and try again.';
    var msgErrorNetwork = form.getAttribute('data-newsletter-msg-error-network') || 'Something went wrong. Please try again.';
    var submitBusy = form.getAttribute('data-newsletter-submit-busy') || '…';

    function clearServerErrors() {
        form.querySelectorAll('.footer-newsletter-error').forEach(function (el) { el.remove(); });
    }

    function setStatus(type, text) {
        if (!statusEl) return;
        statusEl.textContent = text || '';
        statusEl.hidden = !text;
        statusEl.classList.remove('footer-newsletter-status--success', 'footer-newsletter-status--error');
        if (type === 'success') statusEl.classList.add('footer-newsletter-status--success');
        if (type === 'error') statusEl.classList.add('footer-newsletter-status--error');
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearServerErrors();
        setStatus('', '');

        var fd = new FormData(form);
        var prevText = submitBtn ? submitBtn.textContent : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = submitBusy;
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': tokenInput ? tokenInput.value : ''
            },
            body: fd,
            credentials: 'same-origin'
        }).then(function (res) {
            return res.json().then(function (data) {
                return { ok: res.ok, status: res.status, data: data };
            }).catch(function () {
                return { ok: false, status: res.status, data: { message: msgErrorNetwork } };
            });
        }).then(function (r) {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = prevText;
            }
            if (r.ok) {
                setStatus('success', (r.data && r.data.message) ? r.data.message : msgSuccess);
                form.reset();
                return;
            }
            var msg = (r.data && r.data.message) ? r.data.message : msgErrorGeneric;
            if (r.data && r.data.errors && r.data.errors.email && r.data.errors.email[0]) {
                msg = r.data.errors.email[0];
            }
            setStatus('error', msg);
        }).catch(function () {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = prevText;
            }
            setStatus('error', msgErrorNetwork);
        });
    });
})();
</script>
