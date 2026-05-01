@extends('layouts.admin')

@section('title', 'Messages')

@section('content')

<div class="page-header-bar">
    <h1>Contact Messages</h1>
    <span class="badge bg-danger" style="font-size:.8rem;">{{ $contacts->where('is_read', false)->count() }} Unread</span>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($contacts->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p>No messages yet.</p>
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
                            <th width="130">Actions</th>
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
                                    <span class="badge {{ $contact->is_read ? 'badge-active' : 'badge-unread' }} px-2 py-1">
                                        {{ $contact->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this message?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Del</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $contacts->links() }}</div>
        @endif
    </div>
</div>

@endsection
