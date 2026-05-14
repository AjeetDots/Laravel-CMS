<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\MenuLinkDirectory;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('parent_id') === '' || $this->input('parent_id') === false) {
            $this->merge(['parent_id' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'label'          => 'required|string|max:100',
            'menu_link_mode' => 'required|in:preset,custom',
            'url'            => [
                'nullable',
                'string',
                'max:500',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $mode = (string) $this->input('menu_link_mode', '');
                    if (! in_array($mode, ['preset', 'custom'], true)) {
                        $fail('Invalid link mode.');

                        return;
                    }
                    $msg = MenuLinkDirectory::validationErrorForUrl($mode, $value === null ? '' : (string) $value);
                    if ($msg !== null) {
                        $fail($msg);
                    }
                },
            ],
            'target'     => 'in:_self,_blank',
            'parent_id'  => 'nullable|exists:menus,id',
            'sort_order' => ['integer', 'min:0', SortOrderRules::uniqueAmong('menus', ['parent_id' => 'parent_id'])],
            'is_active'  => 'boolean',
        ];
    }
}
