<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HospitalityApi
{
    protected string $baseUrl;
    protected ?string $apiKey;

    public function __construct(?string $baseUrl = null, ?string $apiKey = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? (string) config('services.hospitality.base_url'), '/');
        $this->apiKey = $apiKey ?? config('services.hospitality.api_key');
    }

    protected function client()
    {
        $client = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout(10);

        if ($this->apiKey) {
            $client = $client->withToken($this->apiKey);
        }

        return $client;
    }

    public function getProducts(): array
    {
        $response = $this->client()
            ->get('/cms-lifemedia/public/api/v1/web/products')
            ->throw();

        return [
            'raw' => $response->json(),
            'data' => $response->json('data') ?? [],
        ];
    }
}
