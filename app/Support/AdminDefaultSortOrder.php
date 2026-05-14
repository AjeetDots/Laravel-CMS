<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Default "next" sort_order for admin create forms: max(existing) + 1 within optional scopes.
 * Empty sibling group uses 0 (first row), matching prior "start at zero" behaviour.
 */
final class AdminDefaultSortOrder
{
    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed|null>  $scopes  Column => value; null value scopes with whereNull()
     */
    public static function next(string $modelClass, array $scopes = []): int
    {
        /** @var Builder $query */
        $query = $modelClass::query();
        foreach ($scopes as $column => $value) {
            if ($value === null) {
                $query->whereNull($column);
            } else {
                $query->where($column, $value);
            }
        }

        $max = $query->max('sort_order');

        return (int) ($max ?? -1) + 1;
    }
}
