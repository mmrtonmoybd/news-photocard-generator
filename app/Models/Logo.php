<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Logo extends Model
{
    protected $fillable = [
        'name', 'logo', 'user_id'
    ];

    protected static function booted(): void
        {
            static::creating(function (Logo $logo) {
                $logo->user_id = auth()->user()->id;
            });
            
            static::addGlobalScope('user_scope', function (Builder $query) {
                if (auth()->hasUser()) {
                    $query->where('user_id', auth()->user()->id);
                }
            });
        }
}
