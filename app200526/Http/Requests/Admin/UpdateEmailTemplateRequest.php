<?php
namespace App\Http\Requests\Admin;
use App\Models\EmailTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailTemplateRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        $id = $this->route('email_template')?->id;
        return [
            'name'          => 'required|string|max:255',
            'slug'          => "nullable|string|max:255|unique:email_templates,slug,{$id}",
            'template_type' => ['required', 'string', Rule::in(array_keys(EmailTemplate::$templateTypeLabels))],
            'subject'       => 'required|string|max:255',
            'body'          => 'required|string',
            'is_active'     => 'nullable|boolean',
        ];
    }
}
