<style>
    :root {
        --login-page-bg-1: #ebe6df;
        --login-page-bg-2: #f5f1ea;
        --login-card-bg: #fdfcfa;
        --login-panel-bg: #faf7f2;
        --login-charcoal: #2a2622;
        --login-muted: #5c574e;
        --login-border: #e0d9ce;
        --login-input-border: #d4cdc2;
        --login-btn: #b8975a;
        --login-btn-hover: #a3844f;
        --login-link: #8a7348;
        --login-link-hover: #6b5a38;
        --login-focus: rgba(184, 151, 90, 0.38);
        --login-input-text: #2a2622;
        --login-gold-line: rgba(184, 151, 90, 0.55);
    }
    * { box-sizing: border-box; }
    body.auth-login-page {
        margin: 0;
        min-height: 100vh;
        font-family: 'Inter', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(16px, 4vw, 40px);
        background:
            radial-gradient(ellipse 100% 80% at 50% 0%, rgba(184, 151, 90, 0.14) 0%, transparent 52%),
            radial-gradient(ellipse 60% 40% at 0% 100%, rgba(138, 115, 72, 0.06) 0%, transparent 45%),
            linear-gradient(168deg, var(--login-page-bg-1) 0%, var(--login-page-bg-2) 48%, #efeae2 100%);
        color: var(--login-charcoal);
    }
    .auth-login-shell {
        width: 100%;
        max-width: 960px;
    }
    .auth-login-card {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 520px;
        border-radius: 20px;
        overflow: hidden;
        background: var(--login-card-bg);
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.65) inset,
            0 8px 32px rgba(42, 38, 34, 0.08),
            0 28px 64px rgba(42, 38, 34, 0.1),
            0 0 0 1px rgba(224, 217, 206, 0.9);
    }
    .auth-login-visual {
        position: relative;
        min-height: 100%;
        overflow: hidden;
        background: #e5e1da;
    }
    .auth-login-visual__photo {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .auth-login-form-panel {
        padding: clamp(32px, 5vw, 52px) clamp(24px, 4vw, 44px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.5) 0%, transparent 28%),
            linear-gradient(180deg, #fdfcfa 0%, #f8f5ef 45%, #f4f0e8 100%);
        border-left: 1px solid var(--login-border);
    }
    .auth-login-form-column {
        width: 100%;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    .auth-login-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
        margin-bottom: clamp(24px, 4vw, 36px);
    }
    .auth-login-brand__crest {
        width: 100%;
        max-width: min(100%, 340px);
        margin-left: auto;
        margin-right: auto;
    }
    .auth-login-brand::after {
        content: '';
        display: block;
        width: min(100px, 40vw);
        height: 1px;
        margin: 1.35rem auto 0;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(184, 151, 90, 0.2),
            var(--login-gold-line),
            rgba(184, 151, 90, 0.2),
            transparent
        );
    }
    .auth-login-brand__logo--primary {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-height: clamp(120px, 28vw, 210px);
        max-width: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        object-position: center;
        filter: drop-shadow(0 10px 26px rgba(42, 38, 34, 0.1));
    }
    .auth-login-brand__favicon--primary {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: clamp(72px, 18vw, 96px);
        height: clamp(72px, 18vw, 96px);
        flex-shrink: 0;
        border-radius: 14px;
        object-fit: contain;
        padding: 10px;
        background: #fff;
        border: 1px solid var(--login-input-border);
        box-shadow: 0 8px 22px rgba(42, 38, 34, 0.08);
    }
    .auth-login-brand__subtitle {
        font-family: 'Cormorant Garamond', Georgia, 'Times New Roman', serif;
        margin: 1.05rem 0 0;
        font-size: clamp(0.8125rem, 2.1vw, 0.9375rem);
        font-weight: 600;
        font-style: italic;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--login-link);
        line-height: 1.5;
        max-width: 100%;
        text-align: center;
    }
    .auth-login-page .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--login-charcoal);
        margin-bottom: 8px;
    }
    .auth-login-page .auth-field-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }
    .auth-login-page .auth-field-row .form-label {
        margin-bottom: 0;
    }
    .auth-login-page .form-control {
        border: 1px solid var(--login-input-border);
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.95rem;
        color: var(--login-input-text);
        background: #fff;
        width: 100%;
    }
    .auth-login-page .form-control::placeholder {
        color: #8a847a;
    }
    .auth-login-page .form-control:focus {
        outline: none;
        border-color: var(--login-btn);
        box-shadow: 0 0 0 3px var(--login-focus);
    }
    .auth-login-page .auth-login-pw-wrap.pw-reveal {
        position: relative;
    }
    .auth-login-page .auth-login-pw-wrap .pw-reveal__input {
        padding-right: 3rem;
    }
    .auth-login-page .auth-login-pw-toggle.pw-reveal__btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: var(--login-muted);
        width: 2.5rem;
        height: 2.5rem;
        padding: 0;
        margin: 0;
        border-radius: 8px;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .auth-login-page .auth-login-pw-toggle.pw-reveal__btn i {
        font-size: 0.95rem;
        line-height: 1;
        pointer-events: none;
    }
    .auth-login-page .auth-login-pw-toggle.pw-reveal__btn:hover {
        color: var(--login-charcoal);
        background: rgba(245, 243, 240, 0.95);
    }
    .auth-login-page .auth-login-pw-toggle.pw-reveal__btn:focus-visible {
        outline: 2px solid var(--login-btn);
        outline-offset: 2px;
    }
    .auth-login-forgot {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--login-link);
        text-decoration: none;
        white-space: nowrap;
    }
    .auth-login-forgot:hover {
        color: var(--login-link-hover);
        text-decoration: underline;
    }
    .auth-login-page .form-check {
        margin-top: 14px;
        margin-bottom: 0;
    }
    .auth-login-page .form-check-input {
        width: 1.05rem;
        height: 1.05rem;
        margin-top: 0.15rem;
        border-radius: 4px;
        border: 2px solid var(--login-input-border);
        background-color: #fff;
    }
    .auth-login-page .form-check-input:checked {
        background-color: var(--login-btn);
        border-color: var(--login-btn);
    }
    .auth-login-page .form-check-label {
        font-size: 0.875rem;
        color: var(--login-muted);
        padding-left: 2px;
    }
    .auth-login-page .btn-auth-login {
        width: 100%;
        margin-top: 22px;
        padding: 14px 20px;
        border: none;
        border-radius: 12px;
        background: var(--login-btn);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .auth-login-page .btn-auth-login:hover {
        background: var(--login-btn-hover);
        color: #fff;
    }
    .auth-login-page .btn-auth-login:active {
        transform: translateY(1px);
    }
    .auth-login-alert {
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.875rem;
        margin-bottom: 18px;
        line-height: 1.45;
    }
    .auth-login-alert--error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }
    .auth-login-alert--ok {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
    }
    .auth-login-footer {
        text-align: center;
        margin-top: 22px;
        font-size: 0.8125rem;
    }
    .auth-login-footer a {
        color: var(--login-muted);
        text-decoration: none;
        font-weight: 500;
    }
    .auth-login-footer a:hover {
        color: var(--login-charcoal);
        text-decoration: underline;
    }
    @media (max-width: 799px) {
        .auth-login-card {
            grid-template-columns: 1fr;
            min-height: unset;
        }
        .auth-login-visual {
            min-height: 220px;
            aspect-ratio: 16 / 10;
        }
        .auth-login-form-panel {
            border-left: none;
            border-top: 1px solid var(--login-border);
        }
    }
</style>
