<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:200',
            'title_line_2' => 'nullable|string|max:200',
            'title_line_3' => 'nullable|string|max:200',
            'title_line_4' => 'nullable|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'lead_text'   => 'nullable|string|max:2000',
            'image'       => ImageUploadRules::required(2048),
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:200',
            'sort_order'  => 'integer',
            'panel'       => 'in:main,right_top,right_bottom',
            'is_active'   => 'boolean',
        ];
    }
}
