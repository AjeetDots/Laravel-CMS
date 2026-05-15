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
                <form action="{{ isset($template->id) ? route('admin.email-templates.update', $template) : route('admin.email-templates.store') }}" method="POST">
                    @csrf
                    @if(isset($template->id))
                        @method('PUT')
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Template Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Template Type *</label>
                        <select name="template_type" class="form-select" required>
                            <option value="" disabled {{ old('template_type', $template->template_type) ? '' : 'selected' }}>Choose template type</option>
                            @foreach($templateTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('template_type', $template->template_type) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $template->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <label class="form-label mb-0" for="emailTemplateSubject">Email Subject *</label>
                            <button type="button"
                                    class="btn btn-link btn-sm p-0 text-primary text-decoration-none lh-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#emailShortcodeGuideModal"
                                    title="How shortcodes work">
                                <i class="fas fa-circle-info" aria-hidden="true"></i>
                                <span class="visually-hidden">Open shortcode guide</span>
                            </button>
                        </div>
                        <input type="text" name="subject" id="emailTemplateSubject" class="form-control" value="{{ old('subject', $template->subject) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="emailTemplateBody">Email Body *</label>
                        <textarea name="body" id="emailTemplateBody" class="form-control wysiwyg" rows="10" required>{{ old('body', $template->body) }}</textarea>
                        <div class="form-text mt-2">Use shortcodes from the reference panel.</div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>

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
            <div class="card-header d-flex align-items-center justify-content-between gap-2 py-2">
                <span class="fw-semibold">Available shortcodes</span>
                <button type="button"
                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal"
                        data-bs-target="#emailShortcodeGuideModal"
                        title="How shortcodes work">
                    <i class="fas fa-circle-info" aria-hidden="true"></i>
                    <span>Guide</span>
                </button>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Token</th><th>Meaning</th></tr></thead>
                    <tbody>
                    @foreach($shortcodeReference as $token => $description)
                        <tr>
                            <td><code>{{ $token }}</code></td>
                            <td class="small">{{ $description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(isset($template->id) && !empty($template->placeholders))
                    <hr>
                    <small class="text-muted">Detected in current subject/body:</small><br>
                    @foreach($template->placeholders as $ph)
                        <code class="me-1 px-1 rounded" style="background:#f1f5f9;">{{ '{' . '{' . $ph . '}' . '}' }}</code>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Guide only (tokens stay in the sidebar panel) --}}
<div class="modal fade" id="emailShortcodeGuideModal" tabindex="-1" aria-labelledby="emailShortcodeGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="emailShortcodeGuideModalLabel">Shortcode guide</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Shortcodes are <strong>fixed placeholders</strong> (the tokens listed in <em>Available shortcodes</em>). Copy them into the subject or body <strong>exactly</strong> as shown, including the double curly braces.</p>
                <p class="mb-2">When the email is sent, each token is replaced with the real value from the enquiry or the system (for example the visitor’s name or the message they typed).</p>
                <p class="mb-0 text-muted small">If a token is misspelled or not in the list, it may be left unchanged or appear empty.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
            </div>
        </div>
    </div>
</div>
@endsection
