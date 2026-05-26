<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTermsCondition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'applicable_for',
        'is_active',
    ];

    protected $casts = [
        'applicable_for' => 'array',
        'is_active' => 'boolean',
    ];
}
