<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | CMS Admin</title>
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 260px;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --dark: #0f172a;
            --sidebar-bg: #0f172a;
            --sidebar-border: rgba(255,255,255,.06);
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; min-height: 100vh; }

        /* Sidebar */
        .sidebar { position: fixed; left: 0; top: 0; width: var(--sidebar-w); height: 100vh; background: var(--sidebar-bg); overflow-y: auto; z-index: 1000; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid var(--sidebar-border); }
        .sidebar-brand a { color: #fff; text-decoration: none; font-size: 1.25rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
        .sidebar-brand .brand-icon { width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-section-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #475569; padding: 16px 10px 8px; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: .88rem; font-weight: 500; transition: all .2s; margin-bottom: 2px; }
        .sidebar-link i { width: 18px; text-align: center; font-size: .9rem; }
        .sidebar-link:hover { color: #fff; background: rgba(255,255,255,.06); }
        .sidebar-link.active { color: #fff; background: var(--primary); }
        .sidebar-link .badge { margin-left: auto; font-size: .7rem; }

        /* Main content */
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        /* Top bar */
        .topbar { background: #fff; padding: 0 28px; height: 64px; display: flex; align-items: center; justify-content: between; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 100; }
        .topbar-left { flex: 1; }
        .topbar-title { font-size: 1.1rem; font-weight: 700; color: var(--dark); }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 10px; cursor: pointer; }
        .topbar-user .avatar { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), #60a5fa); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: .9rem; }

        /* Page content */
        .page-content { padding: 28px; flex: 1; }
        .page-header-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-header-bar h1 { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin: 0; }

        /* Cards */
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 10px rgba(0,0,0,.06); }
        .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; font-weight: 600; border-radius: 12px 12px 0 0 !important; }
        .card-body { padding: 24px; }

        /* Stat cards */
        .stat-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 10px rgba(0,0,0,.06); display: flex; align-items: center; gap: 18px; }
        .stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .stat-number { font-size: 2rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-label { font-size: .82rem; color: #64748b; margin-top: 4px; font-weight: 500; }

        /* Table */
        .table { font-size: .9rem; }
        .table th { font-weight: 600; color: #475569; border-color: #f1f5f9; background: #f8fafc; }
        .table td { vertical-align: middle; border-color: #f1f5f9; color: #334155; }
        .table tbody tr:hover { background: #f8fafc; }

        /* Badges */
        .badge-active { background: #dcfce7; color: #16a34a; }
        .badge-inactive { background: #fee2e2; color: #dc2626; }
        .badge-unread { background: #fef3c7; color: #d97706; }

        /* Forms */
        .form-label { font-weight: 500; font-size: .88rem; color: #374151; margin-bottom: 6px; }
        .form-control, .form-select { border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: .92rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        textarea.form-control { min-height: 120px; }

        /* Buttons */
        .btn { font-weight: 500; border-radius: 8px; font-size: .88rem; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm { padding: 6px 14px; }

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

        /* Image preview */
        .img-preview { max-height: 80px; border-radius: 8px; border: 1px solid #e2e8f0; }

        /* Sidebar submenus */
        .sidebar-submenu { list-style: none; padding: 0; margin: 0; overflow: hidden; max-height: 0; transition: max-height .25s ease; }
        .sidebar-submenu.open { max-height: 200px; }
        .sidebar-submenu li a { display: flex; align-items: center; gap: 10px; padding: 7px 14px 7px 40px; border-radius: 6px; color: #94a3b8; text-decoration: none; font-size: .83rem; font-weight: 500; transition: all .2s; margin-bottom: 1px; }
        .sidebar-submenu li a:hover { color: #fff; background: rgba(255,255,255,.06); }
        .sidebar-submenu li a.active { color: #fff; background: rgba(37,99,235,.45); }
        .sidebar-link .submenu-arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .sidebar-link.has-submenu.open .submenu-arrow { transform: rotate(90deg); }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform .3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.css">
    @yield('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
            <span class="brand-icon"><i class="fas fa-bolt"></i></span>
            CMS Admin
        </a>
    </div>
    <div class="sidebar-nav">
        <div class="nav-section-label">Main</div>
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
        <a href="{{ route('admin.gallery.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery.index') || request()->routeIs('admin.gallery.create') || request()->routeIs('admin.gallery.edit') ? 'active' : '' }}">
            <i class="fas fa-photo-video"></i> Gallery
        </a>
        <a href="{{ route('admin.gallery-categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery-categories*') ? 'active' : '' }}">
            <i class="fas fa-folder-tree"></i> Gallery categories
        </a>
        <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
            <i class="fas fa-quote-right"></i> Testimonials
        </a>
        @php $pagesOpen = request()->routeIs('admin.pages*'); @endphp
        <a href="#" class="sidebar-link has-submenu {{ $pagesOpen ? 'open active' : '' }}"
           data-submenu="submenu-pages">
            <i class="fas fa-file-alt"></i> Pages
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $pagesOpen ? 'open' : '' }}" id="submenu-pages">
            <li><a href="{{ route('admin.pages.index') }}"  class="{{ request()->routeIs('admin.pages.index')  ? 'active' : '' }}"><i class="fas fa-list fa-xs"></i> All Pages</a></li>
            <li><a href="{{ route('admin.pages.create') }}" class="{{ request()->routeIs('admin.pages.create') ? 'active' : '' }}"><i class="fas fa-plus fa-xs"></i> Add Page</a></li>
        </ul>

        <div class="nav-section-label">Communication</div>
        @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
        <a href="{{ route('admin.enquiries.index') }}" class="sidebar-link {{ request()->routeIs('admin.enquiries*') || request()->routeIs('admin.contacts*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> Enquiries
            @if($unreadCount > 0)
                <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.email-templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.email-templates*') ? 'active' : '' }}">
            <i class="fas fa-mail-bulk"></i> Email Templates
        </a>

        <div class="nav-section-label">Navigation & Extras</div>
        <a href="{{ route('admin.menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
            <i class="fas fa-bars"></i> Menus
        </a>
        <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Brands
        </a>
        @php $blogOpen = request()->routeIs('admin.blog*') || request()->routeIs('admin.categories*'); @endphp
        <a href="#" class="sidebar-link has-submenu {{ $blogOpen ? 'open active' : '' }}"
           data-submenu="submenu-blog">
            <i class="fas fa-pen-nib"></i> Blog
            <i class="fas fa-chevron-right submenu-arrow"></i>
        </a>
        <ul class="sidebar-submenu {{ $blogOpen ? 'open' : '' }}" id="submenu-blog">
            <li><a href="{{ route('admin.blog.index') }}"       class="{{ request()->routeIs('admin.blog.index')       ? 'active' : '' }}"><i class="fas fa-list fa-xs"></i> All Posts</a></li>
            <li><a href="{{ route('admin.blog.create') }}"      class="{{ request()->routeIs('admin.blog.create')      ? 'active' : '' }}"><i class="fas fa-plus fa-xs"></i> Add Post</a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*')      ? 'active' : '' }}"><i class="fas fa-folder fa-xs"></i> Categories</a></li>
        </ul>
        <a href="{{ route('admin.newsletter.index') }}" class="sidebar-link {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}">
            <i class="fas fa-paper-plane"></i> Newsletter
        </a>

        <div class="nav-section-label">System</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profile
        </a>
        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Site
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn btn-sm btn-light d-lg-none me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            <a href="{{ route('home') }}" class="btn btn-sm btn-light" target="_blank">
                <i class="fas fa-eye me-1"></i>View Site
            </a>
            @php
                $adminUser = Auth::user();
                $adminName = $adminUser?->name ?: 'Admin';
                $adminInitial = strtoupper(substr($adminName, 0, 1));
            @endphp
            <div class="dropdown">
                <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="avatar">{{ $adminInitial }}</div>
                    <div>
                        <div style="font-weight:600; font-size:.85rem; color:#0f172a;">{{ $adminName }}</div>
                        <div style="font-size:.75rem; color:#64748b;">Administrator</div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });

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
            // Close all
            document.querySelectorAll('.sidebar-submenu').forEach(function (s) { s.classList.remove('open'); });
            document.querySelectorAll('.sidebar-link.has-submenu').forEach(function (l) { l.classList.remove('open'); });
            if (!isOpen) {
                submenu.classList.add('open');
                this.classList.add('open');
            }
        });
    });

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
@yield('scripts')
@stack('scripts')
</body>
</html>
