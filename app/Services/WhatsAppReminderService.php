<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppReminderService
{
    public function send(string $phone, string $message): array
    {
        $provider = (string) config('services.whatsapp.provider', 'cloud');
        $phone = $this->normalizePhone($phone);

        if ($phone === '') {
            return [
                'ok' => false,
                'provider' => $provider,
                'error' => 'Missing phone number.',
            ];
        }

        if ($provider !== 'cloud') {
            return [
                'ok' => false,
                'provider' => $provider,
                'error' => 'Unsupported WhatsApp provider configuration.',
            ];
        }

        $token = (string) config('services.whatsapp.cloud_access_token');
        $phoneNumberId = (string) config('services.whatsapp.cloud_phone_number_id');
        $version = (string) config('services.whatsapp.cloud_api_version', 'v21.0');

        if ($token === '' || $phoneNumberId === '') {
            return [
                'ok' => false,
                'provider' => 'cloud',
                'error' => 'WhatsApp Cloud API is not configured.',
            ];
        }

        $response = Http::timeout(30)
            ->acceptJson()
            ->withToken($token)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message,
                ],
            ]);

        if (! $response->successful()) {
            return [
                'ok' => false,
                'provider' => 'cloud',
                'error' => 'WhatsApp API request failed.',
                'status' => $response->status(),
                'response' => $response->body(),
            ];
        }

        return [
            'ok' => true,
            'provider' => 'cloud',
            'message_id' => data_get($response->json(), 'messages.0.id'),
            'response' => $response->json(),
        ];
    }

    public function normalizePhone(string $phone): string
    {
        $clean = preg_replace('/\D+/', '', $phone) ?? '';

        if ($clean === '') {
            return '';
        }

        if (str_starts_with($clean, '0') && strlen($clean) >= 10) {
            $clean = '92' . substr($clean, 1);
        } elseif (strlen($clean) === 10 && ! str_starts_with($clean, '92')) {
            $clean = '92' . $clean;
        }

        return $clean;
    }
}
