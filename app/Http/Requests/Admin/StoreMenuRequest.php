<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:100',
            'label'      => 'required|string|max:100',
            'url'        => 'nullable|string|max:500',
            'target'     => 'in:_self,_blank',
            'parent_id'  => 'nullable|exists:menus,id',
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ];
    }
}
