<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmailTemplateRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'name'      => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:email_templates,slug',
            'subject'   => 'required|string|max:255',
            'body'      => 'required|string',
            'is_active' => 'nullable|boolean',
        ];
    }
}
