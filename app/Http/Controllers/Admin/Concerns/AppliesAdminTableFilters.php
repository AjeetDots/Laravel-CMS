<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait AppliesAdminTableFilters
{
    /**
     * @param  list<string>  $columns
     */
    protected function applyAdminSearch(Builder $query, ?string $q, array $columns): void
    {
        $term = $q !== null ? trim($q) : '';
        if ($term === '' || $columns === []) {
            return;
        }

        $escaped = '%'.addcslashes($term, '%_\\').'%';

        $query->where(function (Builder $w) use ($columns, $escaped): void {
            foreach ($columns as $col) {
                $w->orWhere($col, 'like', $escaped);
            }
        });
    }

    /** @param  '1'|'0'|''|null  $status */
    protected function applyAdminStatus(Builder $query, ?string $status): void
    {
        if ($status === null || $status === '') {
            return;
        }

        $query->where('is_active', (bool) (int) $status);
    }

    /**
     * Newsletter / contacts: tri-state read filter.
     *
     * @param  '1'|'0'|''|null  $read
     */
    protected function applyAdminReadFilter(Builder $query, ?string $read, string $column = 'is_read'): void
    {
        if ($read === null || $read === '') {
            return;
        }

        $query->where($column, (bool) (int) $read);
    }
}
