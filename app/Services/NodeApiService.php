<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class NodeApiService
{
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.node_api.key');
        $this->apiSecret = config('services.node_api.secret');
        $this->baseUrl = rtrim(config('services.node_api.url'), '/');
    }

    protected function getHeaders()
    {
        $timestamp = now()->timestamp;
        $dataToSign = $this->apiKey . '|' . $timestamp;
        $signature = hash_hmac('sha256', $dataToSign, $this->apiSecret);

        return [
            'x-api-key' => $this->apiKey,
            'x-timestamp' => $timestamp,
            'x-signature' => $signature,
            'Content-Type' => 'application/json',
        ];
    }

    public function get(string $endpoint)
    {
        return Http::withHeaders($this->getHeaders())->get($this->baseUrl . '/' . ltrim($endpoint, '/'));
    }

    public function post(string $endpoint, array $data = [])
    {
        return Http::withHeaders($this->getHeaders())->post($this->baseUrl . '/' . ltrim($endpoint, '/'), $data);
    }

    // Add PUT, DELETE, etc. as needed.
}
