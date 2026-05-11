/**
 * International-style phone widget — country list from data-itl-countries (JSON).
 * No external API. Modes: "split" (hidden country id + national) or "combined" (single hidden phone).
 */
(function () {
    'use strict';

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function b64ToUtf8(b64) {
        try {
            var bin = atob(String(b64).trim());
            if (typeof TextDecoder !== 'undefined') {
                var bytes = new Uint8Array(bin.length);
                for (var i = 0; i < bin.length; i++) {
                    bytes[i] = bin.charCodeAt(i) & 0xff;
                }
                return new TextDecoder('utf-8').decode(bytes);
            }
            return decodeURIComponent(escape(bin));
        } catch (e) {
            return '';
        }
    }

    function parseCountries(root) {
        var b64 = root.getAttribute('data-itl-countries-b64');
        if (b64) {
            try {
                var txt = b64ToUtf8(b64);
                if (txt) {
                    var parsed = JSON.parse(txt);
                    if (parsed && parsed.length) {
                        return parsed;
                    }
                }
            } catch (e0) {
                /* fall through */
            }
        }
        var ta = root.querySelector('textarea.itl-phone__json');
        if (ta && ta.value) {
            try {
                return JSON.parse(ta.value);
            } catch (e1) {
                /* fall through */
            }
        }
        var scriptEl = root.querySelector('script.itl-phone__data[type="application/json"]');
        if (scriptEl && scriptEl.textContent) {
            try {
                return JSON.parse(scriptEl.textContent);
            } catch (e2) {
                return [];
            }
        }
        var raw = root.getAttribute('data-itl-countries');
        if (!raw) return [];
        try {
            return JSON.parse(raw);
        } catch (e3) {
            return [];
        }
    }

    function findCountry(countries, id) {
        var n = parseInt(id, 10);
        if (isNaN(n)) return null;
        for (var i = 0; i < countries.length; i++) {
            if (parseInt(countries[i].id, 10) === n) return countries[i];
        }
        return null;
    }

    function findByIso(countries, iso) {
        var u = String(iso || '').toUpperCase();
        for (var j = 0; j < countries.length; j++) {
            if (String(countries[j].iso_code).toUpperCase() === u) return countries[j];
        }
        return countries[0] || null;
    }

    /** Regional-indicator flag when stored emoji is missing (same logic as PHP CountryFlag). */
    function isoToFlagEmoji(iso) {
        var s = String(iso || '')
            .replace(/[^a-zA-Z]/g, '')
            .toUpperCase();
        if (s.length !== 2) return '';
        var a = s.charCodeAt(0);
        var b = s.charCodeAt(1);
        if (a < 65 || a > 90 || b < 65 || b > 90) return '';
        return String.fromCodePoint(0x1f1e6 + (a - 65), 0x1f1e6 + (b - 65));
    }

    function flagForCountry(c) {
        var fe = String(c.flag_emoji || '').trim();
        if (fe) return fe;
        return isoToFlagEmoji(c.iso_code) || '🌐';
    }

    /** SVG data URL with emoji in &lt;text&gt; — renders flags reliably (seeder emoji, no HTTP API). */
    function flagSvgDataUrl(emoji) {
        var ch = (emoji && String(emoji).trim()) ? String(emoji).trim() : '🌐';
        var safe = escapeHtml(ch);
        var svg =
            '<?xml version="1.0" encoding="UTF-8"?>' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="20" viewBox="0 0 28 20">' +
            '<text x="3" y="15" font-size="12" font-family="Segoe UI Emoji,Apple Color Emoji,Noto Color Emoji,Noto Emoji,sans-serif">' +
            safe +
            '</text></svg>';
        return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
    }

    function initOne(root) {
        var countries = parseCountries(root);
        if (!countries.length) return;

        var mode = root.getAttribute('data-itl-mode') || 'split';
        var defaultIso = (root.getAttribute('data-itl-default-iso') || 'GB').toUpperCase();

        var hiddenCountry = root.querySelector('.itl-phone__hidden-country');
        var hiddenNational = root.querySelector('.itl-phone__hidden-national');
        var hiddenPhone = root.querySelector('.itl-phone__hidden-phone');

        var toggle = root.querySelector('.itl-phone__toggle');
        var flagEl = root.querySelector('.itl-phone__flag');
        var dialEl = root.querySelector('.itl-phone__dial');
        var natInput = root.querySelector('.itl-phone__national-input');
        var dropdown = root.querySelector('.itl-phone__dropdown');
        var searchInput = root.querySelector('.itl-phone__search');
        var listEl = root.querySelector('.itl-phone__list');

        if (!toggle || !flagEl || !dialEl || !natInput || !dropdown || !searchInput || !listEl) return;

        var current = null;
        if (mode === 'split' && hiddenCountry && hiddenCountry.value) {
            current = findCountry(countries, hiddenCountry.value);
        }
        if (!current) current = findByIso(countries, defaultIso) || countries[0];

        function renderTrigger() {
            var url = flagSvgDataUrl(flagForCountry(current));
            var img = flagEl.querySelector('img.itl-phone__flag-img');
            if (img) {
                img.setAttribute('src', url);
            } else {
                flagEl.innerHTML =
                    '<img class="itl-phone__flag-img" src="' +
                    url +
                    '" width="24" height="16" alt="" decoding="async" loading="lazy" draggable="false">';
            }
            dialEl.textContent = current.dial_code || '';
            if (toggle) {
                toggle.setAttribute(
                    'title',
                    (current.name || '') + ' ' + (current.dial_code || '')
                );
            }
            if (mode === 'split' && hiddenCountry) hiddenCountry.value = String(current.id);
        }

        var preserveCombinedEmpty =
            mode === 'combined' && hiddenPhone && hiddenPhone.value && !natInput.value.trim();

        /** National subscriber number only — digits (strip spaces, letters, punctuation). */
        function stripNationalToDigits() {
            var v = natInput.value;
            var cleaned = v.replace(/\D/g, '');
            if (v !== cleaned) {
                natInput.value = cleaned;
            }
        }

        function syncHiddens() {
            var nat = natInput.value.trim();
            if (mode === 'split' && hiddenNational) hiddenNational.value = nat;
            if (mode === 'combined' && hiddenPhone) {
                if (!nat && preserveCombinedEmpty) {
                    return;
                }
                preserveCombinedEmpty = false;
                hiddenPhone.value = nat ? (String(current.dial_code || '').trim() + ' ' + nat).trim() : '';
            }
        }

        function buildList(filterText) {
            var ft = (filterText || '').toLowerCase().trim();
            listEl.innerHTML = '';
            for (var i = 0; i < countries.length; i++) {
                var c = countries[i];
                var hay = (c.name + ' ' + c.dial_code + ' ' + c.iso_code).toLowerCase();
                if (ft && hay.indexOf(ft) === -1) continue;
                var li = document.createElement('li');
                li.setAttribute('role', 'option');
                li.setAttribute('tabindex', '-1');
                li.className = 'itl-phone__option';
                var selected = parseInt(current.id, 10) === parseInt(c.id, 10);
                if (selected) li.setAttribute('aria-selected', 'true');
                li.innerHTML =
                    '<span class="itl-phone__opt-flag"><img class="itl-phone__opt-flag-img" src="' +
                    flagSvgDataUrl(flagForCountry(c)) +
                    '" width="22" height="15" alt="" draggable="false"></span><span class="itl-phone__opt-name">' +
                    escapeHtml(c.name) +
                    '</span><span class="itl-phone__opt-dial">(' +
                    escapeHtml(c.dial_code) +
                    ')</span>' +
                    (selected ? '<span class="itl-phone__check" aria-hidden="true">\u2713</span>' : '');
                (function (country) {
                    li.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                    });
                    li.addEventListener('click', function () {
                        current = country;
                        renderTrigger();
                        syncHiddens();
                        buildList(searchInput.value);
                        closeDd();
                        natInput.focus();
                    });
                })(c);
                listEl.appendChild(li);
            }
        }

        function openDd() {
            dropdown.removeAttribute('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            searchInput.value = '';
            buildList('');
            setTimeout(function () {
                searchInput.focus();
            }, 0);
        }

        function closeDd() {
            dropdown.setAttribute('hidden', 'hidden');
            toggle.setAttribute('aria-expanded', 'false');
        }

        function isOpen() {
            return !dropdown.hasAttribute('hidden');
        }

        renderTrigger();
        stripNationalToDigits();
        syncHiddens();

        natInput.addEventListener('input', function () {
            stripNationalToDigits();
            syncHiddens();
        });
        natInput.addEventListener('blur', function () {
            stripNationalToDigits();
            syncHiddens();
        });

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            if (isOpen()) closeDd();
            else openDd();
        });

        searchInput.addEventListener('input', function () {
            buildList(searchInput.value);
        });

        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) closeDd();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && root.contains(e.target)) closeDd();
        });

        var form = root.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                stripNationalToDigits();
                syncHiddens();
            });
        }
    }

    function initAll() {
        document.querySelectorAll('[data-itl-phone]').forEach(function (root) {
            try {
                initOne(root);
            } catch (e) {
                if (typeof console !== 'undefined' && console.error) {
                    console.error('intl-phone-input init failed', e);
                }
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
