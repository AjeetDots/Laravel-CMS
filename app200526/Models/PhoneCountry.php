<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneCountry extends Model
{
    protected $fillable = [
        'iso_code',
        'name',
        'dial_code',
        'flag_emoji',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public static function listingQuery()
    {
        return static::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
