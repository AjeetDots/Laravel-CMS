@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="page-header-bar">
    <h1>Dashboard</h1>
    <span style="color:#64748b; font-size:.88rem;"><i class="fas fa-calendar me-1"></i>{{ now()->format('F d, Y') }}</span>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe; color:#2563eb;"><i class="fas fa-concierge-bell"></i></div>
            <div>
                <div class="stat-number">{{ $stats['services'] }}</div>
                <div class="stat-label">Services</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7; color:#16a34a;"><i class="fas fa-images"></i></div>
            <div>
                <div class="stat-number">{{ $stats['gallery'] }}</div>
                <div class="stat-label">Gallery Items</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7; color:#d97706;"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="stat-number">{{ $stats['contacts'] }}</div>
                <div class="stat-label">Messages
                    @if($stats['unread'] > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $stats['unread'] }} new</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e8ff; color:#7c3aed;"><i class="fas fa-images"></i></div>
            <div>
                <div class="stat-number">{{ $stats['sliders'] }}</div>
                <div class="stat-label">Sliders</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3; color:#db2777;"><i class="fas fa-quote-right"></i></div>
            <div>
                <div class="stat-number">{{ $stats['testimonials'] }}</div>
                <div class="stat-label">Testimonials</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe; color:#0284c7;"><i class="fas fa-star"></i></div>
            <div>
                <div class="stat-number">{{ $stats['brands'] }}</div>
                <div class="stat-label">Brands</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Quick Actions</span>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus me-1"></i>Add Slider</a>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus me-1"></i>Add Service</a>
                    <a href="{{ route('admin.gallery.create') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus me-1"></i>Upload Gallery</a>
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus me-1"></i>Add Testimonial</a>
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus me-1"></i>Add Brand</a>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus me-1"></i>Create Page</a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-cog me-1"></i>Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Messages -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Messages</span>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        @if($recentContacts->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p class="mb-0">No messages yet.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentContacts as $contact)
                            <tr>
                                <td class="fw-500">{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ Str::limit($contact->subject ?? 'No subject', 40) }}</td>
                                <td>{{ $contact->created_at->diffForHumans() }}</td>
                                <td>
                                    <span class="badge {{ $contact->is_read ? 'badge-active' : 'badge-unread' }} px-2 py-1">
                                        {{ $contact->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
