<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FooterText extends Model
{
    protected $fillable = [
        'text', 'user_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (FooterText $footerText) {
            $footerText->user_id = auth()->user()->id;
        });

        static::addGlobalScope('user_scope', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->where('user_id', auth()->user()->id);
            }
        });
    }
}
