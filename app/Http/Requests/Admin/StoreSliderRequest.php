<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSliderRequest extends FormRequest
{
    use SortOrderValidationMessage;
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'title.unique' => 'Another slider slide already uses this headline (line 1). Change the title or edit the existing slide.',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge(['title' => trim((string) $this->input('title'))]);
        }
    }

    public function rules(): array
    {
        return [
            'title'        => [
                'required',
                'string',
                'max:200',
                Rule::unique('sliders', 'title')->whereNull('deleted_at'),
            ],
            'subtitle'    => 'nullable|string|max:300',
            'lead_text'   => 'nullable|string|max:2000',
            'image'       => ImageUploadRules::required(2048),
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:200',
            'button2_text' => 'nullable|string|max:50',
            'button2_link' => 'nullable|string|max:200',
            'sort_order'  => ['integer', 'min:1', SortOrderRules::uniqueAmong('sliders', ['panel' => 'panel'])],
            'panel'       => 'in:main,right_top,right_bottom',
            'is_active'   => 'boolean',
        ];
    }
}
