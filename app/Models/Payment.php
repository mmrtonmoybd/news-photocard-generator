<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'transaction_id', 'own_transaction_id', 'payment_method', 'amount', 'status', 'package_id'
    ];

    protected static function booted(): void
        {
            static::creating(function (Payment $logo) {
                $logo->user_id = auth()->user()->id;
            });
            
            static::addGlobalScope('user_scope', function (Builder $query) {
                if (auth()->hasUser()) {
                    $query->where('user_id', auth()->user()->id);
                }
            });
        }

    public function package() {
        return $this->belongsTo(Package::class);
    }
}
