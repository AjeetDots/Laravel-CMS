<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | CMS Admin</title>
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

        /* Alert */
        .alert { border: none; border-radius: 10px; font-size: .9rem; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .alert-danger { background: #fee2e2; color: #b91c1c; }

        /* Image preview */
        .img-preview { max-height: 80px; border-radius: 8px; border: 1px solid #e2e8f0; }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform .3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
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
        <a href="{{ route('admin.gallery.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
            <i class="fas fa-photo-video"></i> Gallery
        </a>
        <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
            <i class="fas fa-quote-right"></i> Testimonials
        </a>
        <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Brands
        </a>
        <a href="{{ route('admin.pages.index') }}" class="sidebar-link {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Pages
        </a>

        <div class="nav-section-label">Blog & Newsletter</div>
        <a href="{{ route('admin.blog.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
            <i class="fas fa-pen-nib"></i> Blog Posts
        </a>
        <a href="{{ route('admin.newsletter.index') }}" class="sidebar-link {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}">
            <i class="fas fa-paper-plane"></i> Newsletter
        </a>

        <div class="nav-section-label">Navigation & Messages</div>
        <a href="{{ route('admin.menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
            <i class="fas fa-bars"></i> Menus
        </a>
        <a href="{{ route('admin.contacts.index') }}" class="sidebar-link {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> Messages
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
            <div class="dropdown">
                <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-weight:600; font-size:.85rem; color:#0f172a;">{{ Auth::user()->name }}</div>
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
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });
</script>
@yield('scripts')
</body>
</html>
