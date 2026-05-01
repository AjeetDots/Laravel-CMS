@extends('layouts.admin')

@section('title', 'View Message')

@section('content')

<div class="page-header-bar">
    <h1>View Message</h1>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Messages
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Message Details</span>
                <span class="badge badge-active px-2 py-1">Read</span>
            </div>
            <div class="card-body">
                <div class="mb-4 p-4 rounded" style="background:#f8fafc; border-left:4px solid #2563eb;">
                    <h5 class="fw-700 mb-1">{{ $contact->subject ?? 'No Subject' }}</h5>
                    <p class="text-muted mb-0" style="font-size:.85rem;">Received {{ $contact->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div style="line-height:1.9; font-size:.95rem; color:#334155; white-space:pre-wrap;">{{ $contact->message }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Sender Info</div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-size:.75rem; font-weight:600; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Name</div>
                    <div class="fw-600">{{ $contact->name }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:.75rem; font-weight:600; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Email</div>
                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                </div>
                @if($contact->phone)
                <div class="mb-3">
                    <div style="font-size:.75rem; font-weight:600; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Phone</div>
                    <div>{{ $contact->phone }}</div>
                </div>
                @endif
                <div class="mb-4">
                    <div style="font-size:.75rem; font-weight:600; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Date</div>
                    <div>{{ $contact->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <a href="mailto:{{ $contact->email }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-reply me-2"></i>Reply via Email
                </a>
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                      onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-2"></i>Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
