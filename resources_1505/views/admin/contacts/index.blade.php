@extends('layouts.admin')

@section('title', 'Messages')

@section('content')

<div class="page-header-bar d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div class="d-flex flex-wrap align-items-center gap-3">
        <h1 class="mb-0">Contact Messages</h1>
        <span class="badge bg-danger" style="font-size:.8rem;">{{ $unreadCount }} Unread</span>
    </div>
    <a href="{{ request()->routeIs('admin.contacts.index') ? route('admin.contacts.export') : route('admin.enquiries.export') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-file-export me-1"></i> Export CSV
    </a>
</div>

@php
    $contactShowRoute = request()->routeIs('admin.enquiries.*') ? 'admin.enquiries.show' : 'admin.contacts.show';
    $contactDestroyRoute = request()->routeIs('admin.enquiries.*') ? 'admin.enquiries.destroy' : 'admin.contacts.destroy';
@endphp

@include('admin.partials.listing-toolbar', ['showStatus' => false, 'showRead' => true])

<div class="card">
    <div class="card-body p-0">
        @if($contacts->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p>No messages match your filters.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" data-admin-dt>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Client Mail</th>
                            <th>Admin Mail</th>
                            <th>Status</th>
                            <th width="130" data-dt-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr class="{{ !$contact->is_read ? 'fw-600' : '' }}">
                                <td class="fw-500">
                                    @if(!$contact->is_read)
                                        <span class="badge bg-danger me-1" style="font-size:.65rem;">NEW</span>
                                    @endif
                                    {{ $contact->name }}
                                </td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ Str::limit($contact->subject ?? 'No subject', 40) }}</td>
                                <td>{{ $contact->created_at->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $cStatus = $contact->client_mail_status ?: 'pending';
                                    @endphp
                                    <span class="badge px-2 py-1 {{ $cStatus === 'sent' ? 'badge-active' : ($cStatus === 'failed' ? 'badge-inactive' : 'bg-secondary') }}"
                                          data-bs-toggle="tooltip" title="{{ $contact->client_mail_reason ?: 'No log message' }}">
                                        {{ ucfirst($cStatus) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $aStatus = $contact->admin_mail_status ?: 'pending';
                                    @endphp
                                    <span class="badge px-2 py-1 {{ $aStatus === 'sent' ? 'badge-active' : ($aStatus === 'failed' ? 'badge-inactive' : 'bg-secondary') }}"
                                          data-bs-toggle="tooltip" title="{{ $contact->admin_mail_reason ?: 'No log message' }}">
                                        {{ ucfirst($aStatus) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $contact->is_read ? 'badge-active' : 'badge-unread' }} px-2 py-1">
                                        {{ $contact->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route($contactShowRoute, $contact) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           data-bs-toggle="tooltip" title="View Message"><i class="fas fa-eye"></i></a>
                                        <form action="{{ route($contactDestroyRoute, $contact) }}" method="POST" class="d-inline"
                                              data-delete-confirm="This enquiry will be removed from the inbox.">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-icon btn-outline-danger"
                                                    data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
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
