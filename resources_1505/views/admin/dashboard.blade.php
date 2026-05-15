@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<div class="page-header-bar">
    <h1>Dashboard</h1>
    <span class="text-slate-admin"><i class="fas fa-calendar me-1"></i>{{ now()->format('F d, Y') }}</span>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <a href="{{ route('admin.enquiries.index') }}" style="text-decoration:none;">
            <div class="stat-card">
                <div class="stat-icon stat-icon--a"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['enquiries'] }}</div>
                    <div class="stat-label">Total Enquiries
                        @if($stats['unread_enquiries'] > 0)
                            <span class="badge admin-pill-new rounded-pill ms-1">{{ $stats['unread_enquiries'] }} new</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-4">
        <a href="{{ route('admin.services.index') }}" style="text-decoration:none;">
            <div class="stat-card">
                <div class="stat-icon stat-icon--b"><i class="fas fa-concierge-bell"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['services'] }}</div>
                    <div class="stat-label">Services</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-4">
        <a href="{{ route('admin.finishes.index') }}" style="text-decoration:none;">
            <div class="stat-card">
                <div class="stat-icon stat-icon--c"><i class="fas fa-paint-brush"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['finishes'] }}</div>
                    <div class="stat-label">Finishes</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-4">
        <a href="{{ route('admin.gallery.index') }}" style="text-decoration:none;">
            <div class="stat-card">
                <div class="stat-icon stat-icon--e"><i class="fas fa-images"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['gallery'] }}</div>
                    <div class="stat-label">Gallery Items</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-4">
        <a href="{{ route('admin.settings.index') }}" style="text-decoration:none;">
            <div class="stat-card">
                <div class="stat-icon stat-icon--f"><i class="fas fa-cog"></i></div>
                <div>
                    <div class="stat-number" style="font-size:1.2rem;">Site</div>
                    <div class="stat-label">Settings</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-header">Quick Actions</div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.finishes.create') }}"  class="btn btn-outline-primary btn-sm"><i class="fas fa-paint-brush me-1"></i>Add Finish</a>
            <a href="{{ route('admin.services.create') }}"  class="btn btn-outline-primary btn-sm"><i class="fas fa-concierge-bell me-1"></i>Add Service</a>
            <a href="{{ route('admin.gallery.create') }}"   class="btn btn-outline-primary btn-sm"><i class="fas fa-upload me-1"></i>Upload Gallery</a>
            <a href="{{ route('admin.pages.create') }}"     class="btn btn-outline-primary btn-sm"><i class="fas fa-file-alt me-1"></i>Create Page</a>
            <a href="{{ route('admin.settings.index') }}"   class="btn btn-outline-secondary btn-sm"><i class="fas fa-cog me-1"></i>Settings</a>
        </div>
    </div>
</div>

<!-- Recent Enquiries -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Enquiries</span>
        <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        @if($recentEnquiries->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                No enquiries yet.
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt data-dt-page-length="10">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th width="80" class="text-center">Status</th>
                            <th width="80" data-dt-orderable="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentEnquiries as $enquiry)
                        <tr>
                            <td class="fw-500">{{ $enquiry->name }}</td>
                            <td>{{ $enquiry->email }}</td>
                            <td>{{ $enquiry->phone ?? '—' }}</td>
                            <td>{{ $enquiry->created_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <span class="badge {{ $enquiry->is_read ? 'badge-active' : 'badge-unread' }} px-2 py-1">
                                    {{ $enquiry->is_read ? 'Read' : 'Unread' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-sm btn-outline-primary">View</a>
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
