<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        
        Log::info('PaystackService initialized', [
            'has_secret_key' => !empty($this->secretKey),
            'secret_key_length' => strlen($this->secretKey ?? ''),
        ]);
    }

    /**
     * Initialize transaction
     */
    public function initializeTransaction(array $data): ?array
    {
        Log::info('Paystack initializeTransaction started', [
            'amount' => $data['amount'],
            'email' => $data['email'],
            'reference' => $data['reference']
        ]);
        
        if (empty($this->secretKey)) {
            Log::error('Paystack secret key is missing!');
            return null;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'timeout' => 60,
            ])->post($this->baseUrl . '/transaction/initialize', $data);

            $statusCode = $response->status();
            $responseBody = $response->body();
            
            Log::info('Paystack API Response', [
                'status_code' => $statusCode,
                'response' => $responseBody
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Paystack initialization successful', [
                    'authorization_url' => $result['data']['authorization_url'] ?? null
                ]);
                return $result;
            } else {
                Log::error('Paystack API error', [
                    'status' => $statusCode,
                    'body' => $responseBody
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Paystack exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Verify transaction
     */
    public function verifyTransaction(string $reference): ?array
    {
        Log::info('Paystack verifyTransaction started', ['reference' => $reference]);
        
        if (empty($this->secretKey)) {
            Log::error('Paystack secret key is missing!');
            return null;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'timeout' => 60,
            ])->get($this->baseUrl . '/transaction/verify/' . $reference);

            $statusCode = $response->status();
            $responseBody = $response->body();
            
            Log::info('Paystack Verification API Response', [
                'status_code' => $statusCode,
                'reference' => $reference
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Log the transaction status
                $transactionStatus = $result['data']['status'] ?? 'unknown';
                Log::info('Paystack verification successful', [
                    'reference' => $reference,
                    'transaction_status' => $transactionStatus,
                    'amount' => $result['data']['amount'] ?? null,
                    'currency' => $result['data']['currency'] ?? null
                ]);
                
                return $result;
            }

            Log::error('Paystack verification failed', [
                'status' => $statusCode,
                'body' => $responseBody,
                'reference' => $reference
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Paystack verification exception', [
                'message' => $e->getMessage(),
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Get transaction details
     */
    public function getTransactionDetails(string $reference): ?array
    {
        $response = $this->verifyTransaction($reference);
        
        if ($response && isset($response['status']) && $response['status']) {
            return $response['data'] ?? null;
        }
        
        return null;
    }

    /**
     * Check if transaction is successful
     */
    public function isTransactionSuccessful(string $reference): bool
    {
        $response = $this->verifyTransaction($reference);
        
        return $response && 
               isset($response['status']) && 
               $response['status'] && 
               isset($response['data']['status']) && 
               $response['data']['status'] === 'success';
    }
}