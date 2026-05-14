<style>
    :root {
        --auth-primary: #b79860;
        --auth-primary-dark: #9a7e4f;
        --auth-dark: #17120e;
        --auth-dark-mid: #2f2418;
        --auth-muted: #9a8b72;
    }
    * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
    body.auth-guest {
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        background:
            radial-gradient(ellipse 120% 80% at 50% 20%, rgba(183, 152, 96, 0.22) 0%, transparent 55%),
            linear-gradient(160deg, var(--auth-dark) 0%, #1a1410 45%, var(--auth-dark-mid) 100%);
        color: #0f172a;
    }
    .auth-card {
        background: #fff;
        border-radius: 20px;
        padding: 44px 40px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.45);
    }
    .auth-brand {
        text-align: center;
        margin-bottom: 22px;
    }
    .auth-brand img {
        max-height: 56px;
        max-width: 220px;
        width: auto;
        object-fit: contain;
        display: inline-block;
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.2));
    }
    .auth-mark {
        width: 64px;
        height: 64px;
        margin: 0 auto 12px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--auth-primary), var(--auth-primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', Georgia, serif;
        font-style: italic;
        font-weight: 600;
        font-size: 1.35rem;
        letter-spacing: 0.08em;
        color: #fff;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
    }
    .auth-title {
        font-size: 1.55rem;
        font-weight: 800;
        color: #0f172a;
        text-align: center;
        margin: 0 0 6px;
        letter-spacing: -0.02em;
    }
    .auth-sub {
        color: #64748b;
        text-align: center;
        font-size: 0.9rem;
        margin: 0 0 28px;
        line-height: 1.45;
    }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #374151; }
    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.92rem;
    }
    .form-control:focus {
        border-color: var(--auth-primary);
        box-shadow: 0 0 0 3px rgba(183, 152, 96, 0.2);
    }
    .auth-card .form-control[readonly] {
        background-color: #f1f5f9;
        cursor: default;
        color: #334155;
    }
    .auth-card .form-control[readonly]:focus {
        border-color: #e2e8f0;
        box-shadow: none;
    }
    .btn-auth-primary {
        background: linear-gradient(135deg, var(--auth-primary), var(--auth-primary-dark));
        border: none;
        color: #fff;
        width: 100%;
        padding: 13px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        transition: filter 0.2s, transform 0.15s;
    }
    .btn-auth-primary:hover { filter: brightness(1.05); color: #fff; }
    .btn-auth-primary:active { transform: translateY(1px); }
    .input-icon { position: relative; }
    /* Only the leading icon (envelope / lock). Do not use .input-icon i — it would also target the eye inside the toggle button. */
    .input-icon > i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        z-index: 1;
        font-size: 0.95rem;
    }
    .input-icon .form-control { padding-left: 40px; }
    /* Password visibility toggle (login, reset password) */
    .pw-reveal { position: relative; }
    .input-icon.pw-reveal .pw-reveal__input { padding-right: 3rem; }
    .pw-reveal__btn {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #64748b;
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
    .pw-reveal__btn i {
        font-size: 0.95rem;
        line-height: 1;
        pointer-events: none;
    }
    .pw-reveal__btn:hover { color: #475569; background: rgba(241, 245, 249, 0.95); }
    .pw-reveal__btn:focus-visible { outline: 2px solid rgba(183, 152, 96, 0.45); outline-offset: 2px; }
    .auth-alert {
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.88rem;
        margin-bottom: 18px;
        line-height: 1.45;
    }
    .auth-alert--error { background: #fee2e2; color: #b91c1c; }
    .auth-alert--ok { background: #ecfdf5; color: #047857; }
    .auth-footer-links {
        text-align: center;
        margin-top: 18px;
        font-size: 0.85rem;
        color: #64748b;
    }
    .auth-footer-links a { color: var(--auth-primary-dark); text-decoration: none; font-weight: 600; }
    .auth-footer-links a:hover { text-decoration: underline; }
</style>
