<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppOtpService
{
    protected $apiUrl;
    protected $apiKey;
    protected $senderNumber;

    public function __construct()
    {
        // Ambil dari config atau .env
        $this->apiUrl = config('services.whatsapp.url');
        $this->apiKey = config('services.whatsapp.api_key');
        $this->senderNumber = config('services.whatsapp.sender');
    }

    /**
     * Kirim OTP via WhatsApp
     * @param string $phone Nomor tujuan (format 62xxx)
     * @param string $otp Kode OTP
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendOtp(string $phone, string $otp): array
    {
        try {
            // Validasi config
            if (empty($this->apiKey) || empty($this->senderNumber)) {
                Log::error('WhatsApp configuration not found');
                return [
                    'success' => false,
                    'message' => 'Konfigurasi WhatsApp tidak ditemukan'
                ];
            }

            // Format pesan OTP
            $message = "Kode OTP Anda adalah: *{$otp}*\n\n";
            $message .= "Kode ini berlaku selama 5 menit.\n";
            $message .= "Jangan berikan kode ini kepada siapapun.";

            // Data untuk dikirim
            $postData = [
                'api_key' => $this->apiKey,
                'sender' => $this->senderNumber,
                'number' => $phone,
                'message' => $message,
            ];

            Log::info('Sending OTP via WhatsApp', [
                'phone' => $phone,
                'otp' => $otp
            ]);

            // Kirim via cURL
            $response = $this->sendRequest($postData);

            return $response;
        } catch (\Exception $e) {
            Log::error('WhatsApp OTP Send Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengirim OTP: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send request via cURL
     * @param array $postData
     * @return array
     */
    private function sendRequest(array $postData): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        curl_close($curl);

        Log::info('WhatsApp API Response', [
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $error
        ]);

        if ($error) {
            Log::error("cURL Error: {$error}");
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error
            ];
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'message' => 'OTP berhasil dikirim'
            ];
        }

        Log::error("HTTP Error {$httpCode}: {$response}");
        return [
            'success' => false,
            'message' => "HTTP Error {$httpCode}: {$response}"
        ];
    }
}
