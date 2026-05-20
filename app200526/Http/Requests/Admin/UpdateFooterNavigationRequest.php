<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFooterNavigationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['links_1', 'links_2'] as $key) {
            $links = $this->input($key, []);
            if (! is_array($links)) {
                continue;
            }
            foreach ($links as $i => $row) {
                if (! is_array($row)) {
                    continue;
                }
                if (($row['id'] ?? '') === '' || $row['id'] === false) {
                    $links[$i]['id'] = null;
                }
            }
            $this->merge([$key => $links]);
        }
    }

    public function rules(): array
    {
        return [
            'slot_1_title' => 'required|string|max:120',
            'slot_2_title' => 'required|string|max:120',
            'links_1' => 'nullable|array',
            'links_1.*.id' => [
                'nullable',
                'integer',
                Rule::exists('footer_nav_links', 'id')->where(fn ($q) => $q->where('slot', 1)),
            ],
            'links_1.*.label' => 'nullable|string|max:150',
            'links_1.*.url' => 'nullable|string|max:500',
            'links_1.*.target' => 'nullable|in:_self,_blank',
            'links_2' => 'nullable|array',
            'links_2.*.id' => [
                'nullable',
                'integer',
                Rule::exists('footer_nav_links', 'id')->where(fn ($q) => $q->where('slot', 2)),
            ],
            'links_2.*.label' => 'nullable|string|max:150',
            'links_2.*.url' => 'nullable|string|max:500',
            'links_2.*.target' => 'nullable|in:_self,_blank',
        ];
    }
}
