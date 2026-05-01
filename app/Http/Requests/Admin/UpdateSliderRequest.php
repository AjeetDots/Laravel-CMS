<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'image'       => 'nullable|image|max:2048',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:200',
            'sort_order'  => 'integer',
            'panel'       => 'in:main,right_top,right_bottom',
            'is_active'   => 'boolean',
        ];
    }
}
