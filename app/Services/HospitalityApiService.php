<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HospitalityApiService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected int $timeout;
    protected string $apiPrefix = '/cms-lifemedia/public/api/v1';

    public function __construct(?HttpFactory $http = null)
    {
        $this->http = $http ?? Http::class;
        $this->baseUrl = rtrim((string) config('services.hospitality.base_url'), '/');
        $this->apiKey = config('services.hospitality.api_key');
        $this->timeout = (int) env('HOSPITALITY_TIMEOUT', 10);
    }

    protected function client()
    {
        $client = Http::acceptJson()
            ->timeout($this->timeout)
            ->baseUrl($this->baseUrl);

        if ($this->apiKey) {
            $client = $client->withToken($this->apiKey);
        }

        return $client;
    }

    protected function request(string $method, string $path, array $payload = []): array
    {
        $url = $this->buildPath($path);

        try {
            /** @var Response $response */
            $response = $this->client()->$method($url, $payload);
        } catch (\Throwable $e) {
            Log::channel('hospitality')->error("HTTP {$method} {$url} failed", [
                'payload' => $payload,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        if (!$response->successful()) {
            Log::channel('hospitality')->warning("HTTP {$method} {$url} non-200", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        $json = $response->json();

        if (is_null($json)) {
            Log::channel('hospitality')->error("Invalid JSON from {$url}", ['body' => $response->body()]);
            throw new \RuntimeException("Invalid JSON response from {$url}");
        }

        return $json;
    }

    protected function buildPath(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        return $this->apiPrefix . $path;
    }

    // Generic helpers
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, $query);
    }

    public function post(string $path, array $data = []): array
    {
        return $this->request('post', $path, $data);
    }

    // Endpoint-specific helpers
    public function getBanners(): array
    {
        return $this->get('/web/banners');
    }

    public function getProducts(): array
    {
        return $this->get('/web/products');
    }

    public function getProductCopy(): array
    {
        return $this->get('/web/product-copy');
    }

    public function getProductRegister(): array
    {
        return $this->get('/web/product-register');
    }

    public function getProvinces(): array
    {
        return $this->get('/web/provinces');
    }

    public function getCityDistricts(array $query = []): array
    {
        return $this->get('/web/city-districts', $query);
    }

    public function getSubdistricts(array $query = []): array
    {
        return $this->get('/web/subdistricts', $query);
    }

    public function getVillages(array $query = []): array
    {
        return $this->get('/web/villages', $query);
    }

    public function getCareers(): array
    {
        return $this->get('/web/careers');
    }

    public function getProductBenefits(): array
    {
        return $this->get('/web/product-benefit');
    }

    public function getAboutUs(): array
    {
        return $this->get('/web/about-us');
    }

    public function getProductBanners(): array
    {
        return $this->get('/web/product-banner');
    }

    public function getCompanyDesc(): array
    {
        return $this->get('/web/company-desc');
    }

    public function sendCustomerLead(array $payload): array
    {
        return $this->post('/web/customer-lead', $payload);
    }
}
