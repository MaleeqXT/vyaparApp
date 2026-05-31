<?php

namespace App\Notifications;

use App\Models\Party;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PartyPaymentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Party $party,
        private readonly int $dueDays,
        private readonly float $balance,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Party Payment Reminder',
            'message' => sprintf(
                'Payment reminder for %s is due in %d day(s). Outstanding balance: Rs %s.',
                $this->party->name ?? 'party',
                $this->dueDays,
                number_format($this->balance, 2)
            ),
            'party_id' => $this->party->id,
            'party_name' => $this->party->name,
            'due_days' => $this->dueDays,
            'balance' => $this->balance,
            'url' => route('parties'),
        ];
    }
}
