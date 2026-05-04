<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmailTemplateRequest;
use App\Http\Requests\Admin\UpdateEmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller {

    public function index() {
        $templates = EmailTemplate::orderBy('name')->get();
        return view('admin.email-templates.index', compact('templates'));
    }

    public function create() {
        return view('admin.email-templates.form', ['template' => new EmailTemplate()]);
    }

    public function store(StoreEmailTemplateRequest $request) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $data['is_active']    = $request->boolean('is_active');
        $data['placeholders'] = $this->extractPlaceholders($data['body'] ?? '');

        EmailTemplate::create($data);
        return redirect()->route('admin.email-templates.index')->with('success', 'Email template created.');
    }

    public function edit(EmailTemplate $emailTemplate) {
        return view('admin.email-templates.form', ['template' => $emailTemplate]);
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $data['is_active']    = $request->boolean('is_active');
        $data['placeholders'] = $this->extractPlaceholders($data['body'] ?? '');

        $emailTemplate->update($data);
        return redirect()->route('admin.email-templates.index')->with('success', 'Email template updated.');
    }

    public function destroy(EmailTemplate $emailTemplate) {
        $emailTemplate->delete();
        return back()->with('success', 'Email template deleted.');
    }

    public function show(EmailTemplate $emailTemplate) {
        return redirect()->route('admin.email-templates.edit', $emailTemplate);
    }

    private function extractPlaceholders(string $body): array {
        preg_match_all('/\{\{(\w+)\}\}/', $body, $matches);
        return array_values(array_unique($matches[1] ?? []));
    }
}
