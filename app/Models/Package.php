<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name', 'details', 'is_credit', 'credit', 'days', 'price', 'is_active'
    ];
}
