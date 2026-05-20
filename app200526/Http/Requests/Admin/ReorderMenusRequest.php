<?php

namespace App\Http\Requests\Admin;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReorderMenusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'top_level' => ['required', 'array', 'min:1'],
            'top_level.*' => ['integer', 'distinct', 'exists:menus,id'],
            'children' => ['nullable', 'array'],
            'children.*' => ['array'],
            'children.*.*' => ['integer', 'distinct', 'exists:menus,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $topLevel = $this->input('top_level', []);
            if (! is_array($topLevel)) {
                return;
            }

            $topIds = array_map('intval', $topLevel);
            $topCount = Menu::query()->whereNull('parent_id')->whereIn('id', $topIds)->count();
            if ($topCount !== count(array_unique($topIds))) {
                $validator->errors()->add('top_level', 'One or more menu items are invalid or not top-level.');
            }

            $children = $this->input('children', []);
            if (! is_array($children)) {
                return;
            }

            foreach ($children as $parentId => $childIds) {
                if (! is_array($childIds)) {
                    $validator->errors()->add('children', 'Invalid child order payload.');

                    continue;
                }

                $parentId = (int) $parentId;
                if (! Menu::query()->whereNull('parent_id')->whereKey($parentId)->exists()) {
                    $validator->errors()->add('children', 'Invalid parent menu for child reorder.');

                    continue;
                }

                foreach ($childIds as $childId) {
                    $childId = (int) $childId;
                    if (! Menu::query()->whereKey($childId)->where('parent_id', $parentId)->exists()) {
                        $validator->errors()->add('children', 'A submenu item does not belong to its parent.');
                    }
                }
            }
        });
    }
}
