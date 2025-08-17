<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Builder;

class Frame extends Model
{
    protected $fillable = [
        'image', 'name', 'user_id', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
        {
            static::saving(function (Frame $frame) {
                $frame->user_id = auth()->user()->id;
            });
            
            static::addGlobalScope('user_scope', function (Builder $query) {
                if (auth()->hasUser()) {
                    $query->where('user_id', auth()->user()->id);
                }
            });
        }
}
