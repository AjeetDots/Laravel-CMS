@extends('layouts.admin')
@section('title', 'Email Templates')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1 class="mb-0"><i class="fas fa-mail-bulk me-2 text-primary"></i>Email Templates</h1>
</div>

<div class="card mb-3" style="background:#fff7ed;border:1px solid #fed7aa;">
    <div class="card-body py-3">
        <small class="text-warning-emphasis"><i class="fas fa-info-circle me-1"></i>
        Use tabs to manage templates by recipient type. If a template is <strong>inactive</strong>, that email is not sent.
        Admin notifications require <strong>Admin Notification Email</strong> in Settings.</small>
    </div>
</div>

<ul class="nav nav-pills mb-3">
    @foreach($audienceLabels as $key => $label)
        <li class="nav-item me-2">
            <a class="nav-link {{ $audience === $key ? 'active' : '' }}"
               href="{{ route('admin.email-templates.index', ['audience' => $key]) }}">
                {{ $label }}
            </a>
        </li>
    @endforeach
</ul>

<div class="card">
    <div class="card-header">{{ $audienceLabels[$audience] ?? 'Templates' }} ({{ $templates->count() }})</div>
    <div class="card-body p-0">
        @if($templates->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-envelope fa-3x mb-3 d-block opacity-25"></i>
                No templates available in this tab.
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Placeholders Detected</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="70" class="text-center">Edit</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($templates as $template)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $template->name }}</div>
                        <div class="text-muted" style="font-size:.8rem;">{{ $template->slug }}</div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-dark" style="font-weight:500;font-size:.75rem;">
                            {{ $templateTypeLabels[$template->template_type] ?? $template->template_type ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $template->subject }}</td>
                    <td>
                        @foreach($template->placeholders ?? [] as $ph)
                            <code class="me-1 px-1 rounded" style="background:#f1f5f9;font-size:.8rem;">{{ '{' . '{' . $ph . '}' . '}' }}</code>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @if($template->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="action-btns">
                            <a href="{{ route('admin.email-templates.edit', $template) }}"
                               class="btn btn-sm btn-icon btn-outline-primary"
                               data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
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
