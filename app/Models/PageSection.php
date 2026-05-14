<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'page_id',
        'type',
        'position',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
