<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $favicon = \App\Models\Setting::get('site_favicon');
        $backendLogo = \App\Models\Setting::get('backend_logo');
        $siteNameSetting = trim((string) (\App\Models\Setting::get('site_name') ?? ''));
        $adminBrandSiteName = $siteNameSetting !== '' ? $siteNameSetting : config('app.name');
        $adminPanelLabel = $siteNameSetting !== '' ? $siteNameSetting : config('cms.panel_name');
    @endphp
    <title>@yield('title', 'Dashboard') | {{ $adminPanelLabel }}</title>
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 260px;
            --primary: #b8975a;
            --primary-dark: #927848;
            --dark: #2a2622;
            --sidebar-bg: #17120e;
            --sidebar-border: rgba(255,255,255,.08);
            --admin-page-bg: #f2ede6;
            --admin-card-edge: rgba(42, 38, 34, 0.06);
            --admin-charcoal-muted: #5c5348;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: var(--admin-page-bg); min-height: 100vh; }

        /* Sidebar */
        /* Nav scrolls between brand and footer so the footer never paints over links (fixes blocked submenu clicks). */
        .sidebar { position: fixed; left: 0; top: 0; width: var(--sidebar-w); height: 100vh; background: var(--sidebar-bg); overflow: hidden; z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid var(--sidebar-border); }
        .sidebar-brand a { color: #fff; text-decoration: none; font-size: 1.05rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
        .sidebar-brand .brand-logo { max-height: 44px; max-width: 180px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0 3px 8px rgba(0,0,0,.35)); }
        /* Default mark: ornate “pr” monogram (matches public site wordmark, not a generic icon) */
        .sidebar-brand .admin-brand-wordmark {
            display: flex; flex-direction: column; align-items: flex-start; gap: 5px; min-width: 0; line-height: 1.15;
        }
        .sidebar-brand .admin-brand-wordmark__mono {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-weight: 600;
            font-size: 1.42rem;
            letter-spacing: 0.06em;
            color: #d4b77a;
            line-height: 1;
            text-shadow: 0 2px 14px rgba(0,0,0,.45), 0 0 18px rgba(200, 162, 90, 0.18);
        }
        .sidebar-brand .admin-brand-wordmark__tag {
            font-size: 0.58rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #9a8b72;
            max-width: 210px;
        }
        .sidebar-nav { padding: 16px 12px; flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; }
        .sidebar-footer {
            flex-shrink: 0;
            padding: 14px 16px 18px;
            border-top: 1px solid var(--sidebar-border);
            background: var(--sidebar-bg);
            font-size: .68rem;
            line-height: 1.45;
            color: #6b7280;
            position: relative;
            z-index: 2;
        }
        .sidebar-footer__product { font-weight: 700; letter-spacing: .04em; color: #94a3b8; text-transform: uppercase; }
        .nav-section-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #8e7f67; padding: 16px 10px 8px; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: .88rem; font-weight: 500; transition: all .2s; margin-bottom: 2px; }
        .sidebar-link i { width: 18px; text-align: center; font-size: .9rem; }
        .sidebar-link:hover { color: #fff; background: rgba(255,255,255,.06); }
        .sidebar-link.active { color: #fff; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); }
        .sidebar-link .badge { margin-left: auto; font-size: .7rem; }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Top bar */
        .topbar { background: linear-gradient(180deg, #fffefb 0%, #faf7f2 100%); padding: 0 28px; height: 64px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e4dcd2; position: sticky; top: 0; z-index: 100; min-width: 0; }
        .topbar-left { flex: 1; display: flex; align-items: center; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 10px; cursor: pointer; }
        .topbar-user .avatar { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: .9rem; flex-shrink: 0; }
        .topbar-user .avatar.avatar--photo { padding: 0; overflow: hidden; background: #e2e8f0; }
        .topbar-user .avatar.avatar--photo img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* Page content */
        .page-content { padding: 28px; flex: 1; min-width: 0; width: 100%; }
        .page-header-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .page-header-bar h1 { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin: 0; flex: 1 1 auto; min-width: 0; }
        .page-header-bar .text-slate-admin { color: var(--admin-charcoal-muted); font-size: .88rem; }

        /* Cards */
        .card { border: 1px solid var(--admin-card-edge); border-radius: 12px; box-shadow: 0 1px 10px rgba(42, 38, 34, .05); }
        .card-header { background: linear-gradient(180deg, #fffefb 0%, #faf7f2 100%); border-bottom: 1px solid #ebe4d9; padding: 18px 24px; font-weight: 600; color: var(--dark); border-radius: 12px 12px 0 0 !important; }
        .card-body { padding: 24px; }

        /* Stat cards */
        .stat-card { background: #fffefb; border-radius: 12px; padding: 24px; border: 1px solid var(--admin-card-edge); box-shadow: 0 2px 14px rgba(42, 38, 34, .05); display: flex; align-items: center; gap: 18px; transition: box-shadow .2s, border-color .2s; }
        .stat-card:hover { box-shadow: 0 6px 22px rgba(42, 38, 34, .08); border-color: rgba(184, 151, 90, 0.22); }
        .stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .stat-icon--a { background: linear-gradient(145deg, rgba(184, 151, 90, 0.26), rgba(184, 151, 90, 0.08)); color: #6e5c36; }
        .stat-icon--b { background: linear-gradient(145deg, rgba(146, 120, 72, 0.22), rgba(184, 151, 90, 0.06)); color: #5c4e32; }
        .stat-icon--c { background: linear-gradient(145deg, rgba(42, 38, 34, 0.1), rgba(184, 151, 90, 0.05)); color: #4a4338; }
        .stat-icon--d { background: linear-gradient(145deg, rgba(184, 151, 90, 0.18), rgba(232, 220, 198, 0.5)); color: #7a683e; }
        .stat-icon--e { background: linear-gradient(145deg, rgba(139, 115, 72, 0.2), rgba(250, 246, 240, 0.9)); color: #6b5a38; }
        .stat-icon--f { background: linear-gradient(145deg, rgba(42, 38, 34, 0.07), rgba(184, 151, 90, 0.12)); color: #5a5246; }
        .stat-number { font-size: 2rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-label { font-size: .82rem; color: var(--admin-charcoal-muted); margin-top: 4px; font-weight: 500; }

        /* Table */
        .table { font-size: .9rem; }
        .table th { font-weight: 600; color: var(--admin-charcoal-muted); border-color: #efe8de; background: #faf6f0; }
        .table td { vertical-align: middle; border-color: #efe8de; color: #3d3830; }
        .table tbody tr:hover { background: rgba(184, 151, 90, 0.06); }

        /* DataTables (admin listings) */
        .main-content .dt-container { padding: 0 1rem 1rem; font-size: .88rem; max-width: 100%; min-width: 0; overflow-x: auto; }
        .main-content .dt-container .dt-length label,
        .main-content .dt-container .dt-search label { font-weight: 500; color: var(--admin-charcoal-muted); margin-right: .35rem; }
        .main-content .dt-container .dt-paging .pagination { margin-bottom: 0; justify-content: flex-end; flex-wrap: wrap; gap: .15rem; }

        /* Badges */
        .badge-active { background: rgba(88, 108, 92, 0.12); color: #3d4a40; border: 1px solid rgba(88, 108, 92, 0.22); }
        .badge-inactive { background: #fee2e2; color: #b91c1c; }
        .badge-unread { background: linear-gradient(135deg, rgba(212, 175, 55, 0.28), rgba(184, 151, 90, 0.22)); color: #5c4a28; border: 1px solid rgba(184, 151, 90, 0.4); }

        /* Forms */
        .form-label { font-weight: 500; font-size: .88rem; color: #374151; margin-bottom: 6px; }
        .form-control, .form-select { border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: .92rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(184, 151, 90, 0.22); }
        textarea.form-control { min-height: 120px; }

        /* Buttons */
        .btn { font-weight: 500; border-radius: 8px; font-size: .88rem; }
        .btn-primary { background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%); border-color: var(--primary-dark); color: #fff; }
        .btn-primary:hover { background: linear-gradient(180deg, #c4a86a 0%, var(--primary) 100%); border-color: var(--primary); color: #fff; }
        .btn-sm { padding: 6px 14px; }

        /* Premium outline buttons (replaces default Bootstrap blue in admin) */
        .main-content .btn-outline-primary {
            color: var(--primary-dark);
            border-color: rgba(184, 151, 90, 0.55);
            background: rgba(255, 255, 255, 0.65);
        }
        .main-content .btn-outline-primary:hover,
        .main-content .btn-outline-primary:focus {
            color: #fff;
            background: var(--primary);
            border-color: var(--primary);
        }
        .main-content .btn-outline-secondary {
            color: #4a4338;
            border-color: rgba(74, 67, 56, 0.28);
            background: rgba(255, 255, 255, 0.75);
        }
        .main-content .btn-outline-secondary:hover,
        .main-content .btn-outline-secondary:focus {
            color: #fff;
            background: #4a4338;
            border-color: #4a4338;
        }
        .main-content .topbar .btn-light {
            color: #3d3830;
            border: 1px solid rgba(74, 67, 56, 0.22);
            background: rgba(255, 255, 255, 0.92);
        }
        .main-content .topbar .btn-light:hover {
            background: #fff;
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .admin-pill-new {
            background: linear-gradient(135deg, #a3844f, #8b6f3d) !important;
            color: #fff !important;
            font-weight: 600;
            font-size: 0.65rem;
            letter-spacing: 0.02em;
        }

        /* Icon-only action buttons */
        .btn-icon {
            width: 34px; height: 34px;
            padding: 0;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px;
            font-size: .82rem;
            transition: all .18s;
        }
        .btn-icon.btn-outline-secondary:hover { background: #64748b; color: #fff; border-color: #64748b; }
        .btn-icon.btn-outline-primary:hover   { background: var(--primary); color: #fff; }
        .btn-icon.btn-outline-danger:hover    { background: #dc2626; color: #fff; border-color: #dc2626; }
        .action-btns { display: flex; align-items: center; gap: 6px; }

        /* Alert */
        .alert { border: none; border-radius: 10px; font-size: .9rem; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .alert-danger { background: #fee2e2; color: #b91c1c; }

        /* SweetAlert2 — match admin panel */
        .swal2-popup.admin-swal-popup {
            font-family: 'Inter', sans-serif;
            border-radius: 12px;
            padding: 1.35rem 1.35rem 1.1rem;
        }
        .admin-swal-btn-confirm {
            border-radius: 8px !important;
            padding: 0.5rem 1.15rem !important;
            font-weight: 600 !important;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            border: 1px solid var(--primary-dark) !important;
            color: #fff !important;
        }
        .admin-swal-btn-confirm:focus {
            box-shadow: 0 0 0 3px rgba(184, 151, 90, 0.35) !important;
        }
        .admin-swal-btn-cancel {
            border-radius: 8px !important;
            padding: 0.5rem 1.15rem !important;
            font-weight: 600 !important;
            border: 1px solid rgba(74, 67, 56, 0.28) !important;
            background: #fff !important;
            color: #4a4338 !important;
        }

        /* Image preview */
        .img-preview { max-height: 80px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .admin-staged-image-preview { margin-top: 0.5rem; }
        .admin-staged-image-preview__label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 0.35rem;
        }
        .admin-staged-image-preview img {
            max-height: 160px;
            max-width: 100%;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            object-fit: contain;
            display: block;
            background: #f8fafc;
        }

        /* Sidebar submenus */
        .sidebar-submenu { list-style: none; padding: 0; margin: 0; overflow: hidden; max-height: 0; transition: max-height .25s ease; }
        .sidebar-submenu.open { max-height: 640px; }

        /* Admin: switch between Home page / Finishes content editors */
        .theme-content-nav {
            border: 1px solid #e2e8f0;
            background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
            box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        }
        .theme-content-nav__label {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
        }
        .theme-content-nav__pills {
            display: inline-flex;
            flex-wrap: wrap;
            padding: 4px;
            border-radius: 10px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            gap: 4px !important;
        }
        .theme-content-nav__pills .nav-link {
            border-radius: 8px;
            padding: 7px 14px;
            font-size: .84rem;
            font-weight: 600;
            color: #475569;
            border: 1px solid transparent;
            background: transparent;
            transition: color .15s ease, background .15s ease, border-color .15s ease, box-shadow .15s ease;
        }
        .theme-content-nav__pills .nav-link:hover {
            color: #0f172a;
            background: rgba(255,255,255,.85);
            border-color: #e2e8f0;
        }
        .theme-content-nav__pills .nav-link:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
        .theme-content-nav__pills .nav-link.active {
            color: #0f172a;
            background: #fff;
            border-color: #e2e8f0;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .08);
        }
        .sidebar-submenu li a { display: flex; align-items: center; gap: 10px; padding: 7px 14px 7px 40px; border-radius: 6px; color: #94a3b8; text-decoration: none; font-size: .83rem; font-weight: 500; transition: all .2s; margin-bottom: 1px; }
        .sidebar-submenu li a:hover { color: #fff; background: rgba(255,255,255,.06); }
        .sidebar-submenu li a.active { color: #fff; background: rgba(183,152,96,.36); }
        .sidebar-link .submenu-arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .sidebar-link.has-submenu.open .submenu-arrow { transform: rotate(90deg); }

        /* Sidebar overlay (mobile / narrow tablets) */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 12, 10, 0.55);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.28s ease;
        }
        .sidebar-backdrop.is-visible {
            display: block;
            opacity: 1;
            cursor: pointer;
            touch-action: manipulation;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                pointer-events: none;
                box-shadow: 12px 0 40px rgba(0, 0, 0, 0.35);
            }
            .sidebar.open {
                transform: translateX(0);
                pointer-events: auto;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                max-width: 100%;
            }
            .page-content { padding: 18px 14px 28px; }
            .topbar {
                padding: 10px 14px;
                min-height: 56px;
                height: auto;
                row-gap: 8px;
                flex-wrap: wrap;
            }
            .topbar-left { flex: 0 0 auto; }
            .topbar-right {
                flex: 1 1 auto;
                min-width: 0;
                justify-content: flex-end;
                flex-wrap: wrap;
                row-gap: 8px;
            }
            .topbar-user { min-width: 0; max-width: min(200px, 46vw); }
            .topbar-user > div:last-child { min-width: 0; }
            .topbar-user .dropdown-toggle > div > div:first-child {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .topbar-user .dropdown-toggle > div > div:last-child {
                display: none;
            }
            .page-header-bar { margin-bottom: 18px; }
            .card-body { padding: 18px; }
            .card-header { padding: 14px 18px; }
            .stat-card { padding: 16px; gap: 14px; }
            .stat-number { font-size: 1.65rem; }
            .main-content .dt-container { padding-left: 0.25rem; padding-right: 0.25rem; }
            body.admin-app.admin-sidebar-open {
                overflow: hidden;
            }
        }

        @media (max-width: 767.98px) {
            .page-header-bar { flex-direction: column; align-items: stretch; }
            .page-header-bar > h1 { flex: none; }
            .page-header-bar .btn:not(.btn-sm),
            .page-header-bar > a.btn { width: 100%; }
            .page-header-bar > form .btn { width: 100%; }
        }

        @media (max-width: 575.98px) {
            .page-content { padding: 14px 12px 24px; }
            .page-header-bar h1 { font-size: 1.28rem; }
            .topbar-right .btn.btn-sm { padding-inline: 10px; font-size: 0.8rem; }
            .table { font-size: 0.82rem; }
            .table-responsive { -webkit-overflow-scrolling: touch; }
            .action-btns { flex-wrap: wrap; }
        }

        @media (min-width: 992px) {
            .sidebar-backdrop { display: none !important; }
        }

        /* Jodit / rich editors: avoid forcing page wider than viewport */
        .admin-app .jodit-container,
        .admin-app .jodit-workplace { max-width: 100%; }

        /* Admin flash — fixed toasts (session success / error / warning / info / status) */
        .admin-toast-stack {
            position: fixed;
            top: max(72px, calc(env(safe-area-inset-top, 0px) + 64px));
            right: max(20px, env(safe-area-inset-right, 0px));
            z-index: 1080;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            max-width: min(420px, calc(100vw - 40px));
            pointer-events: none;
        }
        .admin-toast-stack .admin-toast { pointer-events: auto; }
        .admin-toast {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
            padding: 12px 40px 12px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            line-height: 1.45;
            box-shadow: 0 10px 36px rgba(15, 23, 42, 0.14);
            border: 1px solid rgba(15, 23, 42, 0.08);
            animation: adminToastIn 0.35s ease forwards;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .admin-toast--out {
            opacity: 0;
            transform: translateX(22px);
        }
        @keyframes adminToastIn {
            from { opacity: 0; transform: translateX(18px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .admin-toast__icon { flex-shrink: 0; margin-top: 2px; font-size: 1.05rem; }
        .admin-toast__text { flex: 1; min-width: 0; word-break: break-word; }
        .admin-toast__close {
            position: absolute;
            top: 8px;
            right: 10px;
            padding: 4px;
            border: none;
            background: transparent;
            color: inherit;
            opacity: 0.45;
            cursor: pointer;
            line-height: 1;
            border-radius: 6px;
        }
        .admin-toast__close:hover { opacity: 0.85; }
        .admin-toast--success {
            background: #ecfdf5;
            color: #065f46;
            border-color: rgba(16, 185, 129, 0.35);
        }
        .admin-toast--success .admin-toast__icon { color: #059669; }
        .admin-toast--danger {
            background: #fef2f2;
            color: #991b1b;
            border-color: rgba(239, 68, 68, 0.35);
        }
        .admin-toast--danger .admin-toast__icon { color: #dc2626; }
        .admin-toast--warning {
            background: #fffbeb;
            color: #92400e;
            border-color: rgba(245, 158, 11, 0.45);
        }
        .admin-toast--warning .admin-toast__icon { color: #d97706; }
        .admin-toast--info {
            background: #eff6ff;
            color: #1e40af;
            border-color: rgba(59, 130, 246, 0.35);
        }
        .admin-toast--info .admin-toast__icon { color: #2563eb; }
        @media (max-width: 575px) {
            .admin-toast-stack {
                left: max(12px, env(safe-area-inset-left, 0px));
                right: max(12px, env(safe-area-inset-right, 0px));
                max-width: none;
                align-items: stretch;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.css">
    @php $__itlCss = public_path('css/intl-phone-input.css'); $__itlCssV = is_file($__itlCss) ? filemtime($__itlCss) : time(); @endphp
    <link rel="stylesheet" href="{{ asset('css/intl-phone-input.css') }}?v={{ $__itlCssV }}">
    @yield('styles')
</head>
<body class="admin-app">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
            @if($backendLogo)
                <img class="brand-logo" src="{{ asset('storage/' . $backendLogo) }}" alt="Admin Logo">
            @else
                <span class="admin-brand-wordmark">
                    <span class="admin-brand-wordmark__mono">pr</span>
                    <span class="admin-brand-wordmark__tag">{{ $adminBrandSiteName }}</span>
                </span>
            @endif
        </a>
    </div>
    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <div class="nav-section-label">Content</div>
        <a href="{{ route('admin.sliders.index') }}" class="sidebar-link {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
            <i class="fas fa-images"></i> Sliders
        </a>
        <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
            <i class="fas fa-concierge-bell"></i> Services
        </a>
        <a href="{{ route('admin.finishes.index') }}" class="sidebar-link {{ request()->routeIs('admin.finishes*') ? 'active' : '' }}">
            <i class="fas fa-paint-brush"></i> Finishes
        </a>
        <a href="{{ route('admin.portfolio.index') }}" class="sidebar-link {{ request()->routeIs('admin.portfolio*') ? 'active' : '' }}">
            <i class="fas fa-briefcase"></i> Portfolio
        </a>
        @php
            $galleryOpen = request()->routeIs('admin.gallery.*') || request()->routeIs('admin.gallery-categories.*');
            $galleryListActive = request()->routeIs('admin.gallery.*') && ! request()->routeIs('admin.gallery-categories.*');
        @endphp
        <a href="#" class="sidebar-link has-submenu {{ $galleryOpen ? 'active open' : '' }}"
           data-submenu="submenu-gallery"
           aria-expanded="{{ $galleryOpen ? 'true' : 'false' }}">
            <i class="fas fa-photo-video"></i> Gallery
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $galleryOpen ? 'open' : '' }}" id="submenu-gallery">
            <li>
                <a href="{{ route('admin.gallery.index') }}" class="{{ $galleryListActive ? 'active' : '' }}">
                    <i class="fas fa-images fa-xs"></i> All Gallery
                </a>
            </li>
            <li>
                <a href="{{ route('admin.gallery-categories.index') }}" class="{{ request()->routeIs('admin.gallery-categories*') ? 'active' : '' }}">
                    <i class="fas fa-folder-tree fa-xs"></i> Gallery Categories
                </a>
            </li>
        </ul>
        <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
            <i class="fas fa-quote-right"></i> Testimonials
        </a>
        @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
        @php $communicationOpen = request()->routeIs('admin.enquiries*') || request()->routeIs('admin.contacts*') || request()->routeIs('admin.email-templates*') || request()->routeIs('admin.newsletter*'); @endphp
        <a href="#" class="sidebar-link has-submenu {{ $communicationOpen ? 'active open' : '' }}"
           data-submenu="submenu-communication"
           aria-expanded="{{ $communicationOpen ? 'true' : 'false' }}">
            <i class="fas fa-envelope-open-text"></i> Communication
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $communicationOpen ? 'open' : '' }}" id="submenu-communication">
            <li>
                <a href="{{ route('admin.enquiries.index') }}" class="{{ request()->routeIs('admin.enquiries*') || request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                    <i class="fas fa-envelope fa-xs"></i> Email
                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.email-templates.index') }}" class="{{ request()->routeIs('admin.email-templates*') ? 'active' : '' }}">
                    <i class="fas fa-mail-bulk fa-xs"></i> Email Templates
                </a>
            </li>
            <li>
                <a href="{{ route('admin.newsletter.index') }}" class="{{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}">
                    <i class="fas fa-paper-plane fa-xs"></i> Newsletter
                </a>
            </li>
        </ul>

        <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Brands
        </a>
        @php $blogOpen = request()->routeIs('admin.blog*') || request()->routeIs('admin.categories*'); @endphp
        <a href="#" class="sidebar-link has-submenu {{ $blogOpen ? 'active open' : '' }}"
           data-submenu="submenu-blog"
           aria-expanded="{{ $blogOpen ? 'true' : 'false' }}">
            <i class="fas fa-pen-nib"></i> Blog
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $blogOpen ? 'open' : '' }}" id="submenu-blog">
            <li><a href="{{ route('admin.blog.index') }}"       class="{{ request()->routeIs('admin.blog.index')       ? 'active' : '' }}"><i class="fas fa-list fa-xs"></i> All Posts</a></li>
            <li><a href="{{ route('admin.blog.create') }}"      class="{{ request()->routeIs('admin.blog.create')      ? 'active' : '' }}"><i class="fas fa-plus fa-xs"></i> Add Post</a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*')      ? 'active' : '' }}"><i class="fas fa-folder fa-xs"></i> Categories</a></li>
        </ul>
        @php $appearanceOpen = request()->routeIs('admin.pages*') || request()->routeIs('admin.menus*') || request()->routeIs('admin.footer-navigation*'); @endphp
        <a href="#" class="sidebar-link has-submenu {{ $appearanceOpen ? 'active open' : '' }}"
           data-submenu="submenu-appearance"
           aria-expanded="{{ $appearanceOpen ? 'true' : 'false' }}">
            <i class="fas fa-brush"></i> Appearance
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $appearanceOpen ? 'open' : '' }}" id="submenu-appearance">
            <li>
                <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt fa-xs"></i> Pages
                </a>
            </li>
            <li>
                <a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
                    <i class="fas fa-bars fa-xs"></i> Menus
                </a>
            </li>
            <li>
                <a href="{{ route('admin.footer-navigation.edit') }}" class="{{ request()->routeIs('admin.footer-navigation*') ? 'active' : '' }}">
                    <i class="fas fa-columns fa-xs"></i> Footer navigation
                </a>
            </li>
        </ul>

        @php $contentManagementOpen = request()->routeIs('admin.theme-options*'); @endphp
        <a href="#" class="sidebar-link has-submenu {{ $contentManagementOpen ? 'active open' : '' }}"
           data-submenu="submenu-content-management"
           aria-expanded="{{ $contentManagementOpen ? 'true' : 'false' }}">
            <i class="fas fa-pen-to-square"></i> Content Hub
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $contentManagementOpen ? 'open' : '' }}" id="submenu-content-management" role="list">
            <li>
                <a href="{{ route('admin.theme-options.home.index') }}" class="{{ request()->routeIs('admin.theme-options.home*') ? 'active' : '' }}">
                    <i class="fas fa-house fa-xs"></i> Home Page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.finishes.index') }}" class="{{ request()->routeIs('admin.theme-options.finishes*') ? 'active' : '' }}">
                    <i class="fas fa-palette fa-xs"></i> Finishes page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.services.index') }}" class="{{ request()->routeIs('admin.theme-options.services*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell fa-xs"></i> Services page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.gallery.index') }}" class="{{ request()->routeIs('admin.theme-options.gallery*') ? 'active' : '' }}">
                    <i class="fas fa-photo-video fa-xs"></i> Gallery page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.portfolio.index') }}" class="{{ request()->routeIs('admin.theme-options.portfolio*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase fa-xs"></i> Portfolio page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.about.index') }}" class="{{ request()->routeIs('admin.theme-options.about*') ? 'active' : '' }}">
                    <i class="fas fa-address-card fa-xs"></i> About page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.contact.index') }}" class="{{ request()->routeIs('admin.theme-options.contact*') ? 'active' : '' }}">
                    <i class="fas fa-envelope fa-xs"></i> Contact page
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme-options.newsletter-footer.index') }}" class="{{ request()->routeIs('admin.theme-options.newsletter-footer*') ? 'active' : '' }}">
                    <i class="fas fa-paper-plane fa-xs"></i> Footer newsletter
                </a>
            </li>
        </ul>

        <div class="nav-section-label">Settings</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="fas fa-sliders-h"></i> Site settings
        </a>
        <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Account
        </a>
    </div>
    <div class="sidebar-footer">
        <div class="sidebar-footer__product">{{ $adminPanelLabel }}</div>
    </div>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn btn-sm btn-light d-lg-none me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        </div>
        <div class="topbar-right">
            @include('admin.partials.cache-purge-form', [
                'buttonClass' => 'btn btn-sm btn-outline-primary',
                'buttonLabel' => 'Refresh site caches',
            ])
            <a href="{{ route('home') }}" class="btn btn-sm btn-light" target="_blank" rel="noopener noreferrer" title="Open the public website in a new tab">
                <i class="fas fa-external-link-alt me-sm-1" aria-hidden="true"></i><span class="d-none d-sm-inline">View site</span><span class="d-sm-none" aria-hidden="true">Site</span>
            </a>
            @php
                $adminUser = Auth::user();
                $adminName = $adminUser?->name ?: 'Admin';
                $adminInitial = strtoupper(substr($adminName, 0, 1));
            @endphp
            <div class="dropdown">
                <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown">
                    @if($adminUser?->avatar)
                        <div class="avatar avatar--photo" aria-hidden="true">
                            <img src="{{ asset('storage/'.$adminUser->avatar) }}" alt="">
                        </div>
                    @else
                        <div class="avatar">{{ $adminInitial }}</div>
                    @endif
                    <div>
                        <div style="font-weight:600; font-size:.85rem; color:#0f172a;">{{ $adminName }}</div>
                        <div style="font-size:.75rem; color:#64748b;">{{ $adminPanelLabel }}</div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="fas fa-user-circle me-2 text-muted"></i> Account</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="fas fa-sliders-h me-2 text-muted"></i> Site settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Log out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>

    @include('partials.admin-toasts')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script>
(function () {
    function initAdminDataTables() {
        if (typeof DataTable === 'undefined') return;
        document.querySelectorAll('table[data-admin-dt]').forEach(function (table) {
            if (table.dataset.adminDtInit === '1') return;
            table.dataset.adminDtInit = '1';
            var paging = table.getAttribute('data-dt-paging') !== 'false';
            var searching = table.getAttribute('data-dt-searching') !== 'false';
            var ordering = table.getAttribute('data-dt-ordering') !== 'false';
            var pageLen = parseInt(table.getAttribute('data-dt-page-length') || '25', 10);
            var nonOrderable = [];
            var headerRow = table.querySelector('thead tr');
            if (headerRow) {
                headerRow.querySelectorAll('th').forEach(function (th, idx) {
                    if (th.getAttribute('data-dt-orderable') === 'false') {
                        nonOrderable.push(idx);
                    }
                });
            }
            var columnDefs = nonOrderable.length ? [{ orderable: false, targets: nonOrderable }] : [];
            var domTop = searching
                ? '<"row g-2 mb-2 align-items-center"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>'
                : '<"row g-2 mb-2"<"col-sm-12 col-md-6"l>>';
            var domBot = '<"row g-2 mt-2 align-items-center"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>';
            new DataTable(table, {
                paging: paging,
                searching: searching,
                ordering: ordering,
                pageLength: pageLen,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                order: [],
                autoWidth: false,
                columnDefs: columnDefs,
                dom: domTop + 'rt' + domBot,
                language: {
                    search: 'Filter:',
                    searchPlaceholder: 'Filter rows…',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    paginate: {
                        first: '«',
                        previous: '‹',
                        next: '›',
                        last: '»',
                    },
                },
            });
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminDataTables);
    } else {
        initAdminDataTables();
    }
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        if (!sidebar) return;
        var open = !sidebar.classList.contains('open');
        sidebar.classList.toggle('open', open);
        document.body.classList.toggle('admin-sidebar-open', open);
        if (backdrop) {
            backdrop.classList.toggle('is-visible', open);
            backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
    });

    document.getElementById('sidebarBackdrop')?.addEventListener('click', function () {
        var sidebar = document.getElementById('sidebar');
        if (sidebar) sidebar.classList.remove('open');
        document.body.classList.remove('admin-sidebar-open');
        this.classList.remove('is-visible');
        this.setAttribute('aria-hidden', 'true');
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.remove('open');
            document.body.classList.remove('admin-sidebar-open');
            if (backdrop) {
                backdrop.classList.remove('is-visible');
                backdrop.setAttribute('aria-hidden', 'true');
            }
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        if (sidebar && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            document.body.classList.remove('admin-sidebar-open');
            if (backdrop) {
                backdrop.classList.remove('is-visible');
                backdrop.setAttribute('aria-hidden', 'true');
            }
        }
    });

    // SweetAlert2 — confirm delete (DELETE) and optional data-swal-confirm (POST)
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (String(form.method).toLowerCase() !== 'post') return;

        var methodInput = form.querySelector('input[name="_method"]');
        var isDelete = methodInput && String(methodInput.value).toUpperCase() === 'DELETE';
        var swalCustom = form.getAttribute('data-swal-confirm');

        if (!isDelete && !swalCustom) return;
        if (isDelete && form.getAttribute('data-delete-confirm') === '0') return;

        e.preventDefault();
        e.stopPropagation();

        var title, text, confirmText, cancelText, icon;
        if (isDelete) {
            title = 'Confirm deletion';
            text = form.getAttribute('data-delete-confirm') || 'The selected item will be removed from the live website.';
            confirmText = 'Delete';
            cancelText = 'Cancel';
            icon = 'warning';
        } else {
            title = form.getAttribute('data-swal-title') || 'Please confirm';
            text = swalCustom;
            confirmText = form.getAttribute('data-swal-confirm-text') || 'Confirm';
            cancelText = form.getAttribute('data-swal-cancel-text') || 'Cancel';
            icon = form.getAttribute('data-swal-icon') || 'question';
        }

        function fallbackThenSubmit() {
            if (window.confirm(text)) {
                form.submit();
            }
        }

        if (typeof Swal === 'undefined') {
            fallbackThenSubmit();
            return;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            focusCancel: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'admin-swal-popup',
                confirmButton: 'admin-swal-btn-confirm',
                cancelButton: 'admin-swal-btn-cancel',
                actions: 'gap-2',
            },
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }, true);

    // Init Bootstrap tooltips for all icon buttons
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });
    });

    // Collapsible sidebar submenus
    document.querySelectorAll('.sidebar-link.has-submenu').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var submenuId = this.dataset.submenu;
            var submenu   = document.getElementById(submenuId);
            var isOpen    = submenu.classList.contains('open');
            document.querySelectorAll('.sidebar-submenu').forEach(function (s) { s.classList.remove('open'); });
            document.querySelectorAll('.sidebar-link.has-submenu').forEach(function (l) {
                l.classList.remove('open');
                l.setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                submenu.classList.add('open');
                this.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // Staged image preview for file inputs (before save). Skips fields that already implement their own preview.
    (function () {
        var pageContent = document.querySelector('.page-content');
        if (!pageContent) return;

        function clearStagedPreview(input) {
            if (input._adminPreviewObjectUrl) {
                URL.revokeObjectURL(input._adminPreviewObjectUrl);
                input._adminPreviewObjectUrl = null;
            }
            var host = input.nextElementSibling;
            if (host && host.classList && host.classList.contains('admin-staged-image-preview')) {
                host.remove();
            }
        }

        pageContent.addEventListener('change', function (ev) {
            var input = ev.target;
            if (!input || input.tagName !== 'INPUT' || input.type !== 'file') return;
            if (input.getAttribute('data-admin-image-preview') === 'off') return;
            if (input.getAttribute('onchange')) return;
            if (input.id === 'imageInput') return;

            if (!input.files || !input.files.length) {
                clearStagedPreview(input);
                return;
            }

            var file = input.files[0];
            if (!file.type || file.type.indexOf('image/') !== 0) {
                clearStagedPreview(input);
                return;
            }

            var host = input.nextElementSibling;
            if (!host || !host.classList.contains('admin-staged-image-preview')) {
                host = document.createElement('div');
                host.className = 'admin-staged-image-preview';
                var label = document.createElement('span');
                label.className = 'admin-staged-image-preview__label';
                label.textContent = 'Selected file preview';
                var img = document.createElement('img');
                img.alt = 'Preview of selected file';
                host.appendChild(label);
                host.appendChild(img);
                input.insertAdjacentElement('afterend', host);
            }

            var previewImg = host.querySelector('img');
            if (input._adminPreviewObjectUrl) {
                URL.revokeObjectURL(input._adminPreviewObjectUrl);
            }
            input._adminPreviewObjectUrl = URL.createObjectURL(file);
            previewImg.src = input._adminPreviewObjectUrl;
        });
    })();

    // Initialise Jodit on every textarea.wysiwyg found on the page
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('textarea.wysiwyg').forEach(function (el) {
            Jodit.make(el, {
                height: 420,
                minHeight: 300,
                toolbarButtonSize: 'middle',
                theme: 'default',
                language: 'en',
                defaultMode: Jodit.MODE_WYSIWYG,
                cleanHTML: { fillEmptyParagraph: false },
                buttons: [
                    'bold','italic','underline','strikethrough','|',
                    'ul','ol','|',
                    'outdent','indent','|',
                    'font','fontsize','brush','paragraph','|',
                    'image','link','|',
                    'align','|',
                    'hr','table','|',
                    'undo','redo','|',
                    'eraser','copyformat','|',
                    'source'
                ],
                uploader: { insertImageAsBase64URI: true },
                showCharsCounter: true,
                showWordsCounter: true,
                showXPathInStatusbar: false,
            });
        });
    });

</script>
@php $__itlJs = public_path('js/intl-phone-input.js'); $__itlJsV = is_file($__itlJs) ? filemtime($__itlJs) : time(); @endphp
<script src="{{ asset('js/intl-phone-input.js') }}?v={{ $__itlJsV }}" defer></script>
@yield('scripts')
@stack('scripts')
</body>
</html>
