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
        $this->apiUrl = config('services.whatsapp.url');
        $this->apiKey = config('services.whatsapp.api_key');
        $this->senderNumber = config('services.whatsapp.sender');
    }

    public function sendOtp(string $phone, string $otp): array
    {
        try {
            if (empty($this->apiKey) || empty($this->senderNumber)) {
                Log::error('WhatsApp configuration not found');
                return [
                    'success' => false,
                    'message' => 'Konfigurasi WhatsApp tidak ditemukan'
                ];
            }

            $message = "Kode OTP Anda adalah: *{$otp}*\n\n";
            $message .= "Kode ini berlaku selama 5 menit.\n";
            $message .= "Jangan berikan kode ini kepada siapapun.";

            $postData = [
                'api_key' => $this->apiKey,
                'sender' => $this->senderNumber,
                'number' => $phone,
                'message' => $message,
            ];

            $response = $this->sendRequest($postData);

            return $response;
        } catch (\Exception $e) {
            Log::error('WhatsApp OTP send failed', [
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => 'Gagal mengirim OTP'
            ];
        }
    }

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

        if ($error) {
            Log::error('WhatsApp API curl error', [
                'error' => $error
            ]);
            return [
                'success' => false,
                'message' => 'Gagal menghubungi API'
            ];
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'message' => 'OTP berhasil dikirim'
            ];
        }

        Log::error('WhatsApp API error', [
            'http_code' => $httpCode
        ]);
        return [
            'success' => false,
            'message' => 'Gagal mengirim OTP'
        ];
    }
}
