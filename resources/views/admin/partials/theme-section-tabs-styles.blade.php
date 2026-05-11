{{-- Shared pill tabs for Homepage / Finishes page / Services page theme editors --}}
<style>
    .theme-section-tabs {
        border-bottom: 0;
        gap: 8px;
        flex-wrap: wrap;
    }
    .theme-section-tabs .nav-link {
        border: 1px solid #e5dccf;
        border-radius: 999px;
        padding: 8px 14px;
        color: #5c4a30;
        font-weight: 600;
        font-size: .84rem;
        background: #f8f3ea;
        transition: all .18s ease;
    }
    .theme-section-tabs .nav-link:hover {
        border-color: #ceb487;
        color: #3d2f1d;
        background: #fff8ec;
    }
    .theme-section-tabs .nav-link.active {
        color: #fff;
        background: linear-gradient(135deg, #b79860, #8f7447);
        border-color: #8f7447;
        box-shadow: 0 6px 16px rgba(143, 116, 71, .24);
    }
    .theme-section-tabs__panels {
        margin-top: 12px;
        border: 1px solid #efe6d8;
        border-radius: 12px;
        padding: 16px;
        background: #fff;
    }
</style>
