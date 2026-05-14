<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool { return true; }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'slug.unique' => 'A category with this URL slug already exists. Use a different slug or name.',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('parent_id') === '' || $this->input('parent_id') === false) {
            $this->merge(['parent_id' => null]);
        }
        if ($this->has('sort_order') && $this->input('sort_order') === '') {
            $this->merge(['sort_order' => null]);
        }

        $name = trim((string) $this->input('name', ''));
        $slugInput = trim((string) $this->input('slug', ''));
        $slug = $slugInput !== '' ? $slugInput : ($name !== '' ? Str::slug($name) : '');
        if ($slug !== '') {
            $this->merge(['slug' => $slug]);
        }
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:120',
            'slug'        => ['nullable', 'string', 'max:120', Rule::unique('categories', 'slug')->ignore($this->route('category'))],
            'description' => 'nullable|string|max:500',
            'parent_id'   => ['nullable', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'sort_order'  => ['nullable', 'integer', 'min:0', SortOrderRules::uniqueAmong('categories', ['parent_id' => 'parent_id'], $this->route('category'))],
            'is_active'   => 'nullable|boolean',
        ];
    }
}
