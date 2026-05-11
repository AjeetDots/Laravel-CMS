<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSection extends Model
{
    protected $fillable = [
        'section_key',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
