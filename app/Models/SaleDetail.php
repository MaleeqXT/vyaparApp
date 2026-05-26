<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'sales_details';

    protected $fillable = [
        'sale_id',
        'warehouse_id',
        'delivery_person',
        'bilti_no',
        'gate_no',
        'po_no',
        'po_date',
        'city',
        'party_no',
        'goods_name',
        'details_extra',
        'bilti_gari_no',
        'terms_condition_name',
        'terms_condition_text',
        'invoice_extra_fields',
        'payment_term_name',
        'payment_term_days',
        'additional_charges',
        'transportation_details',
        'custom_expenses',
    ];

    protected $casts = [
        'po_date' => 'date',
        'invoice_extra_fields' => 'array',
        'additional_charges' => 'array',
        'transportation_details' => 'array',
        'custom_expenses' => 'array',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
