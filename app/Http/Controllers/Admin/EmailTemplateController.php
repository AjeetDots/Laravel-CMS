<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateEmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $this->ensureDefaultTemplates();
        $audience = request('audience', 'client');
        if (! array_key_exists($audience, EmailTemplate::$templateAudienceLabels)) {
            $audience = 'client';
        }
        $allowedTypes = array_keys(EmailTemplate::$templateTypeLabels);
        $typesForAudience = array_values(array_filter(
            $allowedTypes,
            fn (string $type) => EmailTemplate::audienceForType($type) === $audience
        ));

        $query = EmailTemplate::query()->whereIn('template_type', $typesForAudience);
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['name', 'slug']);
        $templates = $query
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        $templateTypeLabels = EmailTemplate::$templateTypeLabels;
        $audienceLabels = EmailTemplate::$templateAudienceLabels;

        return view('admin.email-templates.index', compact('templates', 'templateTypeLabels', 'audience', 'audienceLabels'));
    }

    public function edit(EmailTemplate $emailTemplate) {
        abort_unless($this->isValidTemplateType($emailTemplate->template_type), 404);

        return view('admin.email-templates.form', [
            'template' => $emailTemplate,
            'templateTypes' => EmailTemplate::$templateTypeLabels,
            'shortcodeReference' => EmailTemplate::shortcodeReference(),
        ]);
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate) {
        abort_unless($this->isValidTemplateType($emailTemplate->template_type), 404);

        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . $emailTemplate->id;
        }
        $data['is_active'] = $request->boolean('is_active');
        $data['placeholders'] = $this->extractPlaceholders(
            ($data['body'] ?? '') . "\n" . ($data['subject'] ?? '')
        );

        $emailTemplate->update($data);
        $emailTemplate->refresh();
        $this->deactivateOtherActive($emailTemplate);

        return redirect()->route('admin.email-templates.index', [
            'audience' => EmailTemplate::audienceForType($emailTemplate->template_type),
        ])->with('success', 'Email template updated.');
    }

    private function extractPlaceholders(string $text): array {
        preg_match_all('/\{\{\s*(\w+)\s*\}\}/', $text, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    private function deactivateOtherActive(EmailTemplate $current): void {
        if (! $current->template_type || ! $current->is_active) {
            return;
        }
        EmailTemplate::query()
            ->where('template_type', $current->template_type)
            ->where('id', '!=', $current->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    private function isValidTemplateType(?string $type): bool
    {
        if (! $type) {
            return false;
        }

        return in_array($type, array_keys(EmailTemplate::$templateTypeLabels), true);
    }

    private function ensureDefaultTemplates(): void
    {
        $defaults = EmailTemplate::fixedTemplateDefaults();

        foreach ($defaults as $type => $row) {
            $template = EmailTemplate::query()
                ->where('template_type', $type)
                ->orWhere('slug', $row['slug'])
                ->first();

            if (! $template) {
                $template = EmailTemplate::create([
                    'template_type' => $type,
                    'name' => $row['name'],
                    'slug' => $row['slug'] . '-' . substr(md5($type), 0, 4),
                    'subject' => $row['subject'],
                    'body' => $row['body'],
                    'is_active' => true,
                ]);
            } else {
                $template->template_type = $type;
                $template->name = $template->name ?: $row['name'];
                $template->subject = $template->subject ?: $row['subject'];
                $template->body = $template->body ?: $row['body'];
                $template->save();
            }

            if ($template->wasRecentlyCreated || empty($template->placeholders)) {
                $template->placeholders = $this->extractPlaceholders($template->subject . "\n" . $template->body);
                $template->save();
            }
        }
    }
}
