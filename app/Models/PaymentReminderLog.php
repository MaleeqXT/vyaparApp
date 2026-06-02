<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReminderLog extends Model
{
    protected $fillable = [
        'sale_id',
        'party_id',
        'party_name',
        'phone',
        'due_date',
        'overdue_days',
        'balance',
        'reminder_type',
        'status',
        'provider',
        'provider_message_id',
        'message',
        'provider_response',
        'sent_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'balance' => 'decimal:2',
        'overdue_days' => 'integer',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
