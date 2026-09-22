<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    private const BASE_URL = 'https://api.paystack.co';

    private array $config = [];

    public function __construct()
    {
        $gateway = PaymentGateway::where('code', 'paystack')->first();
        $this->config = $gateway->config ?? [];
    }

    public function isConfigured(): bool
    {
        return ! empty($this->config['secret_key']);
    }

    public function initialize(string $email, float $amount, string $reference, array $metadata = []): array
    {
        $response = Http::withToken($this->config['secret_key'])
            ->acceptJson()
            ->post(self::BASE_URL.'/transaction/initialize', [
                'email' => $email,
                'amount' => (int) round($amount * 100),
                'currency' => 'GHS',
                'reference' => $reference,
                'callback_url' => route('payment.callback'),
                'metadata' => $metadata,
            ]);

        return $response->json() ?? [];
    }

    public function verify(string $reference): array
    {
        $response = Http::withToken($this->config['secret_key'])
            ->acceptJson()
            ->get(self::BASE_URL.'/transaction/verify/'.$reference);

        return $response->json() ?? [];
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        if (empty($this->config['secret_key'])) {
            return false;
        }

        return hash_equals(
            hash_hmac('sha512', $payload, $this->config['secret_key']),
            $signature
        );
    }
}
