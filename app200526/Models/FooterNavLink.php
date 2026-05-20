<?php

namespace App\Models;

use App\Support\NavLinkPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FooterNavLink extends Model
{
    protected $fillable = [
        'slot',
        'label',
        'url',
        'target',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'slot' => 'integer',
    ];

    public function column(): BelongsTo
    {
        return $this->belongsTo(FooterNavColumn::class, 'slot', 'slot');
    }

    public function matchesCurrentPath(): bool
    {
        return NavLinkPath::matchesCurrentPath($this->url);
    }
}
