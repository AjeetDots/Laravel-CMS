<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

final class SortOrderRules
{
    /**
     * Unique sort_order within rows matching scope columns (from request input).
     * Empty string / missing request value scopes with SQL NULL on that column.
     *
     * @param  array<string, string>  $scopeColumnsToRequestKeys  e.g. ['parent_id' => 'parent_id']
     */
    public static function uniqueAmong(string $table, array $scopeColumnsToRequestKeys, Model|int|null $ignore = null): Unique
    {
        $rule = Rule::unique($table, 'sort_order')->whereNull($table.'.deleted_at');

        if ($scopeColumnsToRequestKeys !== []) {
            $rule = $rule->where(function ($query) use ($scopeColumnsToRequestKeys): void {
                foreach ($scopeColumnsToRequestKeys as $column => $requestKey) {
                    $value = request()->input($requestKey);
                    if ($value === null || $value === '') {
                        $query->whereNull($column);
                    } else {
                        $query->where($column, $value);
                    }
                }
            });
        }

        if ($ignore !== null) {
            $rule = $rule->ignore($ignore);
        }

        return $rule;
    }
}
