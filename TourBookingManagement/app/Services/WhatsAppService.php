<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $baseUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');

        if (empty($this->token)) {
            Log::critical('Fonnte token tidak ditemukan di environment variables');
            throw new \Exception('WhatsApp service token tidak dikonfigurasi');
        }
    }

    /**
     * VERSI SUPER SEDERHANA:
     * - Tidak ada rate limit
     * - Tidak ada spam filter
     * - Tidak normalisasi nomor (asumsikan sudah benar: 628xxx atau 08xxx)
     */
    public function sendMessage(string $to, string $message): array
    {
        try {
            $response = $this->sendViaAPI($to, $message);

            return [
                'success'  => true,
                'response' => $response,
                'phone'    => $to,
            ];
        } catch (\Exception $e) {
            Log::error('Gagal mengirim pesan WhatsApp (minimal service)', [
                'phone' => $to,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
                'phone'   => $to,
            ];
        }
    }

    /**
     * Kirim langsung ke Fonnte pakai cURL
     * → Dibikin semirip mungkin contoh dokumentasi resmi
     */
    protected function sendViaAPI(string $phone, string $message): array
    {
        $curl = curl_init();

        // Coba kirim target dalam bentuk APA ADANYA
        // Misal: 62895xxxxxx (seperti nomor tokenmu di dashboard)
        // Untuk awal: JANGAN kirim countryCode dulu
        $postFields = [
            'target'  => $phone,
            'message' => $message,
            // 'countryCode' => '62', // sementara DIMATIKAN dulu
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_HTTPHEADER     => [
                // Sesuai Fonnte: cuma pakai Authorization
                'Authorization: ' . $this->token,
                // JANGAN set Content-Type manual, biar cURL yang atur
            ],
        ]);

        $responseBody = curl_exec($curl);

        if ($responseBody === false) {
            $err = curl_error($curl);
            curl_close($curl);
            throw new \Exception('cURL error: ' . $err);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Coba decode JSON, tapi kalau gagal tetap simpan raw body
        $data = json_decode($responseBody, true);

        if ($httpCode >= 400) {
            throw new \Exception("HTTP Error {$httpCode}: {$responseBody}");
        }

        if (!is_array($data) || !array_key_exists('status', $data)) {
            // Kalau ternyata bukan JSON / format lain, lempar apa adanya
            throw new \Exception('Respons Fonnte tidak valid: ' . $responseBody);
        }

        if (!$data['status']) {
            $reason = $data['reason'] ?? 'Unknown error from Fonnte';
            throw new \Exception('Fonnte error: ' . $reason);
        }

        return [
            'http_code' => $httpCode,
            'body'      => $data,
        ];
    }
}
