/**
 * Contact enquiry forms (contact page + home band): POST via fetch, no full page reload.
 * Depends on intl-phone-input.js (defer) loading before this script (defer, same order in layout).
 */
(function () {
    function clearFormAlerts(form) {
        form.querySelectorAll('.invalid-feedback').forEach(function (el) {
            el.remove();
        });
        form.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        form.querySelectorAll('[data-itl-phone]').forEach(function (wrap) {
            wrap.classList.remove('is-invalid');
        });
    }

    function feedbackBox(form) {
        var id = form.getAttribute('data-feedback-id');
        return id ? document.getElementById(id) : null;
    }

    function setSummary(box, kind, text) {
        if (!box) {
            return;
        }
        if (!text) {
            box.textContent = '';
            box.className = 'd-none';
            return;
        }
        box.textContent = text;
        box.className = 'mb-3 alert ' + (kind === 'success' ? 'alert-success' : 'alert-danger');
        box.setAttribute('role', 'status');
        box.setAttribute('aria-live', 'polite');
    }

    function applyFieldErrors(form, errors) {
        if (!errors || typeof errors !== 'object') {
            return;
        }
        Object.keys(errors).forEach(function (field) {
            if (field === '_form_context') {
                return;
            }
            var arr = errors[field];
            if (!arr || !arr.length) {
                return;
            }
            var msg = arr[0];
            var input = form.querySelector('[name="' + field + '"]');
            if (!input && field === 'phone') {
                input = form.querySelector('input.itl-phone__hidden-phone[name="phone"]');
            }
            if (!input) {
                return;
            }
            input.classList.add('is-invalid');
            if (field === 'phone') {
                var itl = input.closest('[data-itl-phone]');
                if (itl) {
                    itl.classList.add('is-invalid');
                }
            }
            var fb = document.createElement('div');
            fb.className = 'invalid-feedback d-block';
            fb.textContent = msg;
            if (field === 'phone') {
                var wrap = input.closest('[data-itl-phone]');
                var holder = wrap ? wrap.parentElement : null;
                if (holder) {
                    holder.appendChild(fb);
                }
            } else {
                input.insertAdjacentElement('afterend', fb);
            }
        });
    }

    function bindForm(form) {
        if (!form || form.getAttribute('data-contact-ajax-bound') === '1') {
            return;
        }
        form.setAttribute('data-contact-ajax-bound', '1');
        var tokenInput = form.querySelector('input[name="_token"]');
        var submitBtn = form.querySelector('[type="submit"]');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearFormAlerts(form);
            setSummary(feedbackBox(form), '', '');

            var prevHtml = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '…';
            }

            var fd = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': tokenInput ? tokenInput.value : '',
                },
                body: fd,
                credentials: 'same-origin',
            })
                .then(function (res) {
                    return res.json().then(function (data) {
                        return { ok: res.ok, data: data };
                    }).catch(function () {
                        return {
                            ok: false,
                            data: { message: 'Something went wrong. Please try again.' },
                        };
                    });
                })
                .then(function (r) {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = prevHtml;
                    }
                    var box = feedbackBox(form);
                    if (r.ok) {
                        setSummary(box, 'success', (r.data && r.data.message) || 'Thank you! Your message has been sent.');
                        form.reset();
                        return;
                    }
                    var intro = form.getAttribute('data-error-intro') || 'Please fix the errors below.';
                    if (r.data && r.data.errors) {
                        applyFieldErrors(form, r.data.errors);
                        setSummary(
                            box,
                            'error',
                            r.data.message && String(r.data.message).length ? r.data.message : intro
                        );
                    } else {
                        setSummary(box, 'error', (r.data && r.data.message) || 'Please try again.');
                    }
                })
                .catch(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = prevHtml;
                    }
                    setSummary(feedbackBox(form), 'error', 'Something went wrong. Please try again.');
                });
        });
    }

    function init() {
        document.querySelectorAll('form[data-contact-ajax="1"]').forEach(bindForm);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
