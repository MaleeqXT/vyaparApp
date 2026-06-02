<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Models\PaymentReminderLog;
use App\Models\Sale;
use App\Services\WhatsAppReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendPartyPaymentReminders extends Command
{
    protected $signature = 'parties:send-payment-reminders';

    protected $description = 'Send payment reminder reminders automatically via WhatsApp';

    public function __construct(private readonly WhatsAppReminderService $whatsAppReminderService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $enabled = AppSetting::getValue('payment_reminder', '1') === '1';
        if (! $enabled) {
            $this->info('Payment reminders are disabled.');
            return self::SUCCESS;
        }

        $beforeDays = max(0, (int) AppSetting::getValue('payment_reminder_before_days', '1'));
        $afterDays = max(1, (int) AppSetting::getValue('payment_reminder_after_days', '3'));
        $template = (string) AppSetting::getValue(
            'payment_reminder_message',
            "Dear [Party Name],\n\nYour payment of [Amount] is pending with [Business Name].\n\n[Additional Message]\n\nIf you already have made the payment, kindly ignore this message."
        );
        $businessName = (string) config('app.name', 'My Company');

        $sentCount = 0;
        $skippedCount = 0;
        $failedCount = 0;

        Sale::query()
            ->with(['party:id,name,phone,phone_number_2'])
            ->whereNotNull('due_date')
            ->whereRaw('COALESCE(balance, 0) > 0')
            ->orderBy('due_date')
            ->chunkById(100, function ($sales) use (
                $beforeDays,
                $afterDays,
                $template,
                $businessName,
                &$sentCount,
                &$skippedCount,
                &$failedCount
            ) {
                foreach ($sales as $sale) {
                    $dueDate = Carbon::parse($sale->due_date)->startOfDay();
                    $today = now()->startOfDay();
                    $daysUntilDue = $today->diffInDays($dueDate, false);
                    $dueDaysLabel = '';
                    $reminderType = null;

                    if ($daysUntilDue > 0 && $daysUntilDue === $beforeDays) {
                        $reminderType = 'due_soon';
                        $dueDaysLabel = "{$daysUntilDue} day(s)";
                    } elseif ($daysUntilDue === 0) {
                        $reminderType = 'due_today';
                        $dueDaysLabel = 'Today';
                    } elseif ($daysUntilDue < 0) {
                        $overdueDays = abs($daysUntilDue);
                        $shouldSendOverdue = $overdueDays === 1 || (($overdueDays - 1) % $afterDays === 0);
                        if ($shouldSendOverdue) {
                            $reminderType = 'overdue';
                            $dueDaysLabel = "Overdue by {$overdueDays} day(s)";
                        }
                    }

                    if (! $reminderType) {
                        continue;
                    }

                    $alreadySentToday = PaymentReminderLog::query()
                        ->where('sale_id', $sale->id)
                        ->whereDate('created_at', today())
                        ->exists();

                    if ($alreadySentToday) {
                        $skippedCount++;
                        continue;
                    }

                    $party = $sale->party;
                    $phone = trim((string) ($sale->phone ?: $party?->phone ?: $party?->phone_number_2 ?: ''));
                    $partyName = trim((string) ($party?->name ?: $sale->display_party_name ?: 'Party'));
                    $amount = (float) $sale->balance;

                    $message = strtr($template, [
                        '[Party Name]' => $partyName,
                        '[Amount]' => 'Rs ' . number_format($amount, 2),
                        '[Business Name]' => $businessName,
                        '[Due Days]' => $dueDaysLabel,
                        '[Invoice No]' => (string) ($sale->bill_number ?: $sale->reference_bill_number ?: $sale->id),
                        '[Due Date]' => $dueDate->format('d/m/Y'),
                    ]);

                    $log = PaymentReminderLog::create([
                        'sale_id' => $sale->id,
                        'party_id' => $party?->id,
                        'party_name' => $partyName,
                        'phone' => $phone,
                        'due_date' => $sale->due_date,
                        'overdue_days' => $daysUntilDue < 0 ? abs($daysUntilDue) : null,
                        'balance' => $amount,
                        'reminder_type' => $reminderType,
                        'status' => 'pending',
                        'provider' => config('services.whatsapp.provider', 'cloud'),
                        'message' => $message,
                    ]);

                    if ($phone === '') {
                        $log->update([
                            'status' => 'skipped',
                            'provider_response' => 'Missing phone number.',
                            'sent_at' => now(),
                        ]);
                        $skippedCount++;
                        continue;
                    }

                    $result = $this->whatsAppReminderService->send($phone, $message);

                    if (($result['ok'] ?? false) === true) {
                        $log->update([
                            'status' => 'sent',
                            'provider_message_id' => $result['message_id'] ?? null,
                            'provider_response' => json_encode($result['response'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                            'sent_at' => now(),
                        ]);
                        $sentCount++;
                        continue;
                    }

                    $log->update([
                        'status' => 'failed',
                        'provider_response' => json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'sent_at' => now(),
                    ]);
                    $failedCount++;
                }
            });

        $this->info("Automatic reminders finished. sent={$sentCount}, skipped={$skippedCount}, failed={$failedCount}");
        return self::SUCCESS;
    }
}
