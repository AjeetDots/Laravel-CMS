@extends('layouts.admin')

@section('title', 'Site settings')

@section('styles')
<style>
    .settings-tabs-layout {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    }
    .settings-tabs-layout__nav {
        background: linear-gradient(180deg, #fafbfc 0%, #f1f5f9 100%);
        border-right: 1px solid #e2e8f0;
        min-height: 100%;
        padding: 1rem 0.75rem;
    }
    @media (max-width: 991.98px) {
        .settings-tabs-layout__nav {
            border-right: 0;
            border-bottom: 1px solid #e2e8f0;
        }
    }
    .settings-tabs-layout__nav .nav-link {
        border-radius: 8px;
        padding: 0.65rem 0.85rem;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: left;
        border: 1px solid transparent;
        margin-bottom: 4px;
    }
    .settings-tabs-layout__nav .nav-link:hover {
        background: rgba(183, 152, 96, 0.12);
        color: #3d2f1d;
    }
    .settings-tabs-layout__nav .nav-link.active {
        color: #fff;
        background: linear-gradient(135deg, #b79860, #8f7447);
        border-color: #8f7447;
        box-shadow: 0 4px 12px rgba(143, 116, 71, 0.25);
    }
    .settings-tabs-layout__nav .nav-link i {
        width: 1.1rem;
        text-align: center;
        opacity: 0.9;
    }
    .settings-tabs-layout__panels {
        min-height: 420px;
    }
    .settings-field-help-icon {
        font-size: 1rem;
        vertical-align: middle;
        text-decoration: none !important;
    }
    .settings-field-help-icon:hover,
    .settings-field-help-icon:focus-visible {
        color: #0aa2c0 !important;
    }
    .tooltip.settings-field-tooltip .tooltip-inner {
        max-width: min(22rem, 92vw);
        text-align: left;
    }
</style>
@endsection

@section('content')

@php
    use App\Http\Requests\Admin\UpdateSettingRequest;

    $settingsActiveTab = request()->query('tab', 'general');
    if (! in_array($settingsActiveTab, ['general', 'notifications', 'social', 'logos', 'smtp', 'theme'], true)) {
        $settingsActiveTab = 'general';
    }
    if ($errors->any()) {
        $tabPriority = ['logos', 'social', 'notifications', 'smtp', 'theme', 'general'];
        foreach ($tabPriority as $tab) {
            foreach ($errors->keys() as $field) {
                if (UpdateSettingRequest::settingsTabForField($field) === $tab) {
                    $settingsActiveTab = $tab;
                    break 2;
                }
            }
        }
    }
@endphp

<div class="page-header-bar">
    <div>
        <h1>Site settings</h1>
        <div class="d-flex align-items-start gap-2 mt-1">
            <button type="button"
                class="btn btn-link p-0 text-info settings-field-help-icon flex-shrink-0 mt-0"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="You can edit any tab (General, Notifications, Social, Site logos, SMTP, Theme). One click on Save settings writes every tab—you do not need to open each tab before saving.">
                <i class="fas fa-info-circle" aria-hidden="true"></i>
                <span class="visually-hidden">How saving across tabs works</span>
            </button>
            <p class="text-muted mb-0 small">General details, notifications, social links, branding, outbound email, and public theme colours. One save updates every tab.</p>
        </div>
    </div>
</div>

<form id="site-settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="settings-tabs-layout mb-4">
        <div class="row g-0">
            <div class="col-lg-3 col-xl-2 settings-tabs-layout__nav">
                <div class="nav flex-column nav-pills" id="settingsPageTabs" role="tablist" aria-orientation="vertical">
                    <button class="nav-link @if($settingsActiveTab === 'general') active @endif" id="settings-tab-general" data-bs-toggle="pill" data-bs-target="#settings-pane-general" type="button" role="tab" aria-controls="settings-pane-general" aria-selected="{{ $settingsActiveTab === 'general' ? 'true' : 'false' }}">
                        <i class="fas fa-sliders-h me-2" aria-hidden="true"></i>General
                    </button>
                    <button class="nav-link @if($settingsActiveTab === 'notifications') active @endif" id="settings-tab-notifications" data-bs-toggle="pill" data-bs-target="#settings-pane-notifications" type="button" role="tab" aria-controls="settings-pane-notifications" aria-selected="{{ $settingsActiveTab === 'notifications' ? 'true' : 'false' }}">
                        <i class="fas fa-bell me-2" aria-hidden="true"></i>Notifications
                    </button>
                    <button class="nav-link @if($settingsActiveTab === 'social') active @endif" id="settings-tab-social" data-bs-toggle="pill" data-bs-target="#settings-pane-social" type="button" role="tab" aria-controls="settings-pane-social" aria-selected="{{ $settingsActiveTab === 'social' ? 'true' : 'false' }}">
                        <i class="fas fa-share-alt me-2" aria-hidden="true"></i>Social
                    </button>
                    <button class="nav-link @if($settingsActiveTab === 'logos') active @endif" id="settings-tab-logos" data-bs-toggle="pill" data-bs-target="#settings-pane-logos" type="button" role="tab" aria-controls="settings-pane-logos" aria-selected="{{ $settingsActiveTab === 'logos' ? 'true' : 'false' }}">
                        <i class="fas fa-images me-2" aria-hidden="true"></i>Site logos
                    </button>
                    <button class="nav-link @if($settingsActiveTab === 'smtp') active @endif" id="settings-tab-smtp" data-bs-toggle="pill" data-bs-target="#settings-pane-smtp" type="button" role="tab" aria-controls="settings-pane-smtp" aria-selected="{{ $settingsActiveTab === 'smtp' ? 'true' : 'false' }}">
                        <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>SMTP
                    </button>
                    <button class="nav-link @if($settingsActiveTab === 'theme') active @endif" id="settings-tab-theme" data-bs-toggle="pill" data-bs-target="#settings-pane-theme" type="button" role="tab" aria-controls="settings-pane-theme" aria-selected="{{ $settingsActiveTab === 'theme' ? 'true' : 'false' }}">
                        <i class="fas fa-palette me-2" aria-hidden="true"></i>Theme
                    </button>
                </div>
            </div>
            <div class="col-lg-9 col-xl-10 settings-tabs-layout__panels p-3 p-lg-4">
                <div class="tab-content" id="settingsPageTabsContent">
                    <div class="tab-pane fade @if($settingsActiveTab === 'general') show active @endif" id="settings-pane-general" role="tabpanel" aria-labelledby="settings-tab-general" tabindex="0">
                        @include('admin.settings.partials.tab-general', ['settings' => $settings, 'phoneCountries' => $phoneCountries])
                    </div>
                    <div class="tab-pane fade @if($settingsActiveTab === 'notifications') show active @endif" id="settings-pane-notifications" role="tabpanel" aria-labelledby="settings-tab-notifications" tabindex="0">
                        @include('admin.settings.partials.tab-notifications', ['settings' => $settings])
                    </div>
                    <div class="tab-pane fade @if($settingsActiveTab === 'social') show active @endif" id="settings-pane-social" role="tabpanel" aria-labelledby="settings-tab-social" tabindex="0">
                        @include('admin.settings.partials.tab-social', ['settings' => $settings])
                    </div>
                    <div class="tab-pane fade @if($settingsActiveTab === 'logos') show active @endif" id="settings-pane-logos" role="tabpanel" aria-labelledby="settings-tab-logos" tabindex="0">
                        @include('admin.settings.partials.tab-logos', ['settings' => $settings])
                    </div>
                    <div class="tab-pane fade @if($settingsActiveTab === 'smtp') show active @endif" id="settings-pane-smtp" role="tabpanel" aria-labelledby="settings-tab-smtp" tabindex="0">
                        @include('admin.settings.partials.tab-smtp', ['settings' => $settings])
                    </div>
                    <div class="tab-pane fade @if($settingsActiveTab === 'theme') show active @endif" id="settings-pane-theme" role="tabpanel" aria-labelledby="settings-tab-theme" tabindex="0">
                        @include('admin.settings.partials.tab-theme', ['settings' => $settings])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-save-bar d-flex flex-wrap align-items-center justify-content-end column-gap-3 row-gap-2 mt-3 pt-3 border-top">
        <span class="text-muted small mb-0 text-end" style="max-width: min(22rem, 100%);" id="site-settings-save-hint">
            Saves every tab (General, Notifications, Social, Site logos, SMTP, Theme) in one submission.
        </span>
        <button type="submit" class="btn btn-primary btn-lg flex-shrink-0" aria-describedby="site-settings-save-hint">
            <i class="fas fa-save me-2" aria-hidden="true"></i>Save settings
        </button>
    </div>
</form>

@endsection

@section('scripts')
<script>
(function () {
    var fav = document.getElementById('site_favicon');
    if (fav) {
        fav.addEventListener('change', function () {
            if (this.files[0]) {
                document.getElementById('faviconPreviewImg').src = URL.createObjectURL(this.files[0]);
                var wrap = document.getElementById('faviconPreview');
                wrap.style.display = 'flex';
                wrap.style.alignItems = 'center';
            }
        });
    }

    // Required / pattern failures on a hidden tab: show the right tab before native validation blocks submit.
    var form = document.getElementById('site-settings-form');
    if (!form || typeof bootstrap === 'undefined') return;

    form.addEventListener('submit', function (e) {
        if (form.checkValidity()) return;

        e.preventDefault();
        e.stopPropagation();

        var el = form.querySelector(':invalid');
        if (el) {
            var pane = el.closest('.tab-pane');
            if (pane && pane.id) {
                var trigger = document.querySelector('[data-bs-target="#' + pane.id + '"]');
                if (trigger) {
                    bootstrap.Tab.getOrCreateInstance(trigger).show();
                }
                window.setTimeout(function () {
                    try { el.focus({ preventScroll: false }); } catch (err) { el.focus(); }
                }, 200);
            }
        }
        form.classList.add('was-validated');
    }, false);
})();
</script>
@endsection
