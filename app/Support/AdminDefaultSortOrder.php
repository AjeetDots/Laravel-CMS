<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Default "next" sort_order for admin create forms: max(existing) + 1 within optional scopes (minimum 1).
 */
final class AdminDefaultSortOrder
{
    public const START = 1;

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

        $max = $query->pluck('sort_order')
            ->map(static fn ($value) => max(self::START, (int) $value))
            ->max();

        if ($max === null) {
            return self::START;
        }

        return $max + 1;
    }
}
