<?php
namespace App\Http\Requests\Admin;
use App\Models\EmailTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailTemplateRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:email_templates,slug',
            'template_type' => ['required', 'string', Rule::in(array_keys(EmailTemplate::$templateTypeLabels))],
            'subject'       => 'required|string|max:255',
            'body'          => 'required|string',
            'is_active'     => 'nullable|boolean',
        ];
    }
}
