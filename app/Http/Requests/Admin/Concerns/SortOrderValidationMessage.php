<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Concerns;

trait SortOrderValidationMessage
{
    /**
     * @return array<string, string>
     */
    protected function sortOrderValidationMessages(): array
    {
        return [
            'sort_order.unique' => 'That position number is already taken in this list. Try another number.',
        ];
    }

    public function messages(): array
    {
        return $this->sortOrderValidationMessages();
    }
}
