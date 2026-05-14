<?php
namespace App\Http\Requests\Admin;
use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreFinishRequest extends FormRequest {
    use SortOrderValidationMessage;
    public function authorize() { return true; }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'slug.unique' => 'A finish with this URL slug already exists. Use a different title, set a custom slug, or edit the existing finish.',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('sort_order') && $this->input('sort_order') === '') {
            $this->merge(['sort_order' => null]);
        }

        $title = trim((string) $this->input('title', ''));
        $slugInput = trim((string) $this->input('slug', ''));
        $slug = $slugInput !== ''
            ? Str::slug($slugInput)
            : ($title !== '' ? Str::slug($title) : '');
        if ($slug !== '') {
            $this->merge(['slug' => $slug]);
        }
    }

    public function rules() {
        return [
            'title'          => 'required|string|max:255',
            'slug'           => ['nullable', 'string', 'max:255', Rule::unique('finishes', 'slug')->whereNull('deleted_at')],
            'description'    => 'nullable|string',
            'use_cases'      => 'nullable|string',
            'cover_image'    => ImageUploadRules::nullable(4096),
            'gallery_images' => 'nullable|array',
            'gallery_images.*'=> ImageUploadRules::nullable(4096),
            'tags_raw'       => 'nullable|string',
            'sort_order'     => ['nullable', 'integer', 'min:0', SortOrderRules::uniqueAmong('finishes', [])],
            'is_active'      => 'nullable|boolean',
        ];
    }
}
