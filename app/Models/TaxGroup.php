<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name',
    ];

    public function rates()
    {
        return $this->belongsToMany(TaxRate::class, 'tax_group_rate', 'tax_group_id', 'tax_rate_id');
    }
}
