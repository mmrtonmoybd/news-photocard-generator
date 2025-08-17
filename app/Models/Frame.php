<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $fillable = [
        'image', 'name', 'user_id', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Frame $frame) {
            $frame->user_id = auth()->user()->id;
        });

        
    }
}
