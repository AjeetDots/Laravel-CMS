@extends('layouts.admin')
@section('title', 'Email Templates')
@section('content')

<div class="page-header-bar">
    <h1><i class="fas fa-mail-bulk me-2 text-primary"></i>Email Templates</h1>
    <a href="{{ route('admin.email-templates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Template
    </a>
</div>

<div class="card mb-4" style="background:#fff7ed;border:1px solid #fed7aa;">
    <div class="card-body py-3">
        <small class="text-warning-emphasis"><i class="fas fa-info-circle me-1"></i>
        Use placeholders like <code>{{name}}</code>, <code>{{email}}</code>, <code>{{phone}}</code>, <code>{{message}}</code> in the template body. They will be replaced automatically when an enquiry is submitted.</small>
    </div>
</div>

<div class="card">
    <div class="card-header">All Templates ({{ $templates->count() }})</div>
    <div class="card-body p-0">
        @if($templates->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-envelope fa-3x mb-3 d-block opacity-25"></i>
                No templates yet. <a href="{{ route('admin.email-templates.create') }}">Create your first template</a>.
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Placeholders Detected</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="110" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($templates as $template)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $template->name }}</div>
                        <div class="text-muted" style="font-size:.8rem;">{{ $template->slug }}</div>
                    </td>
                    <td>{{ $template->subject }}</td>
                    <td>
                        @foreach($template->placeholders ?? [] as $ph)
                            <code class="me-1 px-1 rounded" style="background:#f1f5f9;font-size:.8rem;">{{ '{{' . $ph . '}}' }}</code>
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
                            <form action="{{ route('admin.email-templates.destroy', $template) }}" method="POST"
                                  onsubmit="return confirm('Delete this template?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
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
