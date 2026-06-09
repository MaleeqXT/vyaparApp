<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'rate',
    ];

    public function groups()
    {
        return $this->belongsToMany(TaxGroup::class, 'tax_group_rate', 'tax_rate_id', 'tax_group_id');
    }
}
