<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    use SortOrderValidationMessage;
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'name.unique' => 'A brand with this name already exists. Use a different name or edit the existing brand.',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge(['name' => trim((string) $this->input('name'))]);
        }
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:100', Rule::unique('brands', 'name')->whereNull('deleted_at')],
            'logo'       => ImageUploadRules::required(1024),
            'website'    => 'nullable|url|max:200',
            'sort_order' => ['integer', 'min:0', SortOrderRules::uniqueAmong('brands', [])],
            'is_active'  => 'boolean',
        ];
    }
}
