<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key') ?? '';
    }

    /**
     * Create a Xendit Invoice
     *
     * @return array|null
     */
    public function createInvoice(array $params)
    {
        if (empty($this->secretKey)) {
            Log::error('Xendit Secret Key is not set.');

            return null;
        }

        $payload = [
            'external_id' => $params['external_id'],
            'amount' => (int) $params['amount'],
            'payer_email' => $params['payer_email'],
            'description' => $params['description'],
            'invoice_duration' => 86400, // 24 hours
            'customer' => [
                'given_names' => $params['customer_name'],
                'email' => $params['payer_email'],
                'mobile_number' => $params['customer_phone'] ?? '',
            ],
            'success_redirect_url' => route('dashboard'),
            'failure_redirect_url' => route('dashboard'),
            'currency' => 'IDR',
        ];

        try {
            // Xendit uses Basic Auth where the username is the Secret Key and the password is empty.
            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post('https://api.xendit.co/v2/invoices', $payload);

            if ($response->successful()) {
                return $response->json(); // Returns array containing invoice_url
            }

            Log::error('Xendit Create Invoice Failed', [
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Xendit Create Invoice Exception', ['message' => $e->getMessage()]);

            return null;
        }
    }
}
