<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $baseUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');
    }

    public function sendMessage(string $to, string $message): void
    {
        if (! $this->token) {
            Log::warning('Fonnte token tidak di-set.');
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->asForm()->post($this->baseUrl, [
            'target'  => $to,
            'message' => $message,
        ]);

        if ($response->failed()) {
            Log::error('Gagal kirim WA via Fonnte', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        }
    }

    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
