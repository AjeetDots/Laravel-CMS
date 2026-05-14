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
                    <h5 class="fw-700 mb-1">{{ $contact->subject ?? '' }}</h5>
                    <p class="text-muted mb-0" style="font-size:.85rem;">Received {{ $contact->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div style="line-height:1.9; font-size:.95rem; color:#334155; white-space:pre-wrap;">{{ $contact->message }}</div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">Email Delivery Log History</div>
            <div class="card-body p-0">
                @if(($mailLogs ?? collect())->isEmpty())
                    <div class="p-4 text-muted">No email delivery logs found for this enquiry yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th>When</th>
                                <th>Template</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mailLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                                    <td>{{ \App\Models\EmailTemplate::$templateTypeLabels[$log->template_type] ?? $log->template_type }}</td>
                                    <td>{{ $log->to_email ?: '-' }}</td>
                                    <td>
                                        @php
                                            $status = $log->status ?: 'unknown';
                                        @endphp
                                        <span class="badge {{ $status === 'sent' ? 'badge-active' : ($status === 'failed' ? 'badge-inactive' : 'bg-secondary') }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $log->error_message ?: $log->smtp_response ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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
                <div class="mb-3">
                    <div style="font-size:.75rem; font-weight:600; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Client Mail Status</div>
                    <span class="badge {{ $contact->client_mail_status === 'sent' ? 'badge-active' : 'badge-inactive' }}"
                          data-bs-toggle="tooltip" title="{{ $contact->client_mail_reason ?: 'No status note' }}">
                        {{ ucfirst($contact->client_mail_status ?: 'pending') }}
                    </span>
                </div>
                <div class="mb-4">
                    <div style="font-size:.75rem; font-weight:600; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Admin Mail Status</div>
                    <span class="badge {{ $contact->admin_mail_status === 'sent' ? 'badge-active' : 'badge-inactive' }}"
                          data-bs-toggle="tooltip" title="{{ $contact->admin_mail_reason ?: 'No status note' }}">
                        {{ ucfirst($contact->admin_mail_status ?: 'pending') }}
                    </span>
                </div>
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                      data-delete-confirm="This enquiry will be removed from the inbox.">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-2"></i>Delete Message
                    </button>
                </form>
            </div>
        </div>
        @php
            $replyFollowupOpen = $errors->has('reply_method') || $errors->has('reply_message');
            $replyLogCount = ($replyLogs ?? collect())->count();
        @endphp
        <style>
            .reply-followup-toggle { cursor: pointer; }
            .reply-followup-toggle:focus { box-shadow: none; outline: 2px solid rgba(37, 99, 235, 0.35); outline-offset: 2px; }
            .reply-followup-chevron { transition: transform 0.2s ease; display: inline-block; }
            .reply-followup-toggle[aria-expanded="true"] .reply-followup-chevron { transform: rotate(180deg); }
            .reply-followup-notes { min-height: 120px; max-height: 420px; resize: vertical; }
        </style>
        <div class="card mt-4">
            <button type="button"
                    class="card-header reply-followup-toggle w-100 border-0 text-start d-flex justify-content-between align-items-center gap-3 @unless($replyFollowupOpen) collapsed @endunless"
                    data-bs-toggle="collapse"
                    data-bs-target="#replyFollowupCollapse"
                    aria-expanded="{{ $replyFollowupOpen ? 'true' : 'false' }}"
                    aria-controls="replyFollowupCollapse"
                    id="replyFollowupToggle">
                <span class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center gap-1 gap-sm-2 min-w-0">
                    <span class="fw-600">Reply / Follow-up Log</span>
                    <span class="small text-muted fw-normal text-truncate">Log replies &amp; calls — expand to add or view history</span>
                </span>
                <span class="d-flex align-items-center gap-2 flex-shrink-0">
                    @if($replyLogCount > 0)
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $replyLogCount }} {{ $replyLogCount === 1 ? 'entry' : 'entries' }}</span>
                    @else
                        <span class="badge rounded-pill bg-light text-muted border">No logs yet</span>
                    @endif
                    <i class="fas fa-chevron-down reply-followup-chevron text-muted small" aria-hidden="true"></i>
                </span>
            </button>
            <div id="replyFollowupCollapse" class="collapse @if($replyFollowupOpen) show @endif" role="region" aria-labelledby="replyFollowupToggle">
                <div class="card-body">
                    <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Contacted via</label>
                            <select class="form-select" name="reply_method" required>
                                <option value="" disabled {{ old('reply_method', $contact->reply_method) ? '' : 'selected' }}>Select method</option>
                                <option value="email" {{ old('reply_method', $contact->reply_method) === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="phone" {{ old('reply_method', $contact->reply_method) === 'phone' ? 'selected' : '' }}>Phone Call</option>
                                <option value="other" {{ old('reply_method', $contact->reply_method) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reply / Notes</label>
                            <textarea name="reply_message" class="form-control reply-followup-notes" rows="4" required placeholder="e.g. What was discussed or sent…">{{ old('reply_message', $contact->reply_message) }}</textarea>
                            <div class="form-text">Drag the corner to resize the notes area.</div>
                        </div>
                        <button class="btn btn-outline-primary w-100">
                            <i class="fas fa-save me-2"></i>Save Reply Log
                        </button>
                    </form>
                    @if($contact->replied_at)
                        <div class="text-muted small mt-3">Last updated: {{ $contact->replied_at->format('M d, Y h:i A') }}</div>
                    @endif
                    <hr>
                    <div style="font-size:.8rem; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:10px;">History</div>
                    @if(($replyLogs ?? collect())->isEmpty())
                        <div class="small text-muted">No follow-up logs yet.</div>
                    @else
                        <div class="d-flex flex-column gap-2" style="max-height: 280px; overflow-y: auto;">
                            @foreach($replyLogs as $log)
                                <div class="p-2 rounded border" style="background:#f8fafc;">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="badge bg-primary-subtle text-dark">{{ strtoupper($log->reply_method) }}</span>
                                        <small class="text-muted">{{ $log->created_at?->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <div class="small mt-1" style="white-space:pre-wrap;">{{ $log->reply_message }}</div>
                                    <div class="small text-muted mt-1">By: {{ $log->user?->name ?: 'Admin' }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
