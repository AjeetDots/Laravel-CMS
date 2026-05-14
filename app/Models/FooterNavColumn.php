<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One of two fixed footer columns (slot 1 or 2). Title is the visible column heading.
 */
class FooterNavColumn extends Model
{
    protected $primaryKey = 'slot';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'slot',
        'title',
    ];

    public function links()
    {
        return $this->hasMany(FooterNavLink::class, 'slot', 'slot')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }
}
