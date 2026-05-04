@extends('layouts.admin')
@section('title', isset($template->id) ? 'Edit Email Template' : 'Add Email Template')
@section('content')

<div class="page-header-bar">
    <h1>{{ isset($template->id) ? 'Edit Email Template' : 'Add Email Template' }}</h1>
    <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($template->id) ? route('admin.email-templates.update', $template) : route('admin.email-templates.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($template->id)) @method('PUT') @endif

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Template Name *</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $template->name) }}" required
                               placeholder="e.g. Enquiry Confirmation">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control"
                               value="{{ old('slug', $template->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Subject *</label>
                        <input type="text" name="subject" class="form-control"
                               value="{{ old('subject', $template->subject) }}" required
                               placeholder="e.g. Thank you for your enquiry, {{name}}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Body *</label>
                        <textarea name="body" class="form-control wysiwyg" rows="10" required>{{ old('body', $template->body) }}</textarea>
                        <div class="form-text mt-2">
                            Available placeholders: <code>{{name}}</code> <code>{{email}}</code> <code>{{phone}}</code> <code>{{message}}</code> <code>{{date}}</code>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($template->id) ? 'Update Template' : 'Create Template' }}
                        </button>
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Available Placeholders</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Insert these into your template body. They are replaced automatically when an enquiry is submitted.</p>
                <table class="table table-sm">
                    <thead><tr><th>Placeholder</th><th>Replaced With</th></tr></thead>
                    <tbody>
                        <tr><td><code>{{name}}</code></td><td>Customer's full name</td></tr>
                        <tr><td><code>{{email}}</code></td><td>Customer's email</td></tr>
                        <tr><td><code>{{phone}}</code></td><td>Customer's phone</td></tr>
                        <tr><td><code>{{message}}</code></td><td>Enquiry message</td></tr>
                        <tr><td><code>{{date}}</code></td><td>Submission date</td></tr>
                    </tbody>
                </table>
                @if(isset($template->id) && !empty($template->placeholders))
                    <hr>
                    <small class="text-muted">Detected in current body:</small><br>
                    @foreach($template->placeholders as $ph)
                        <code class="me-1 px-1 rounded" style="background:#f1f5f9;">{{ '{{' . $ph . '}}' }}</code>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
