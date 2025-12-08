<?php

namespace App\Console\Commands;

use App\Models\AboutUs;
use App\Models\Banner;
use App\Models\Career;
use App\Models\CompanyDescription;
use App\Models\Product;
use App\Models\ProductBenefit;
use App\Models\ProductCategory;
use App\Models\Province;
use App\Models\CityDistrict;
use App\Models\Subdistrict;
use App\Models\Village;
use App\Services\HospitalityApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncHospitalityData extends Command
{
    protected $signature = 'hospitality:sync {--store-json : Simpan payload mentah ke storage/app/hospitality}';
    protected $description = 'Sync data Hospitality Lifemedia ke database lokal';

    public function handle(HospitalityApiService $api): int
    {
        $this->info('Memulai sync Hospitality...');
        Log::channel('hospitality')->info('Hospitality sync mulai');

        try {
            $this->syncProducts($api);
            $this->syncProductBenefits($api);
            $this->syncBanners($api);
            $this->syncCareers($api);
            $this->syncProvinces($api);
            $this->syncCities($api);
            $this->syncSubdistricts($api);
            $this->syncVillages($api);
            $this->syncCompanyDesc($api);
            $this->syncAboutUs($api);
        } catch (\Throwable $e) {
            Log::channel('hospitality')->error('Hospitality sync gagal', ['error' => $e->getMessage()]);
            $this->error('Sync gagal: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->info('Sync selesai.');
        Log::channel('hospitality')->info('Hospitality sync selesai');
        return self::SUCCESS;
    }

    protected function syncProducts(HospitalityApiService $api): void
    {
        $payload = $api->getProducts();
        $data = Arr::get($payload, 'data', []);

        $this->maybeStoreRaw('products', $payload);

        foreach ($data as $cat) {
            $category = ProductCategory::updateOrCreate(
                ['slug' => $cat['slug'] ?? Str::slug($cat['category'] ?? Str::random(6))],
                [
                    'name' => $cat['category'] ?? 'Unknown',
                    'short_description' => $cat['short_description'] ?? null,
                    'long_description' => $cat['long_description'] ?? null,
                    'show_price' => Arr::get($cat, 'is_price', true) ? 1 : 0,
                ]
            );

            foreach (Arr::get($cat, 'products', []) as $prod) {
                Product::updateOrCreate(
                    ['name' => $prod['name'] ?? Str::uuid()->toString()],
                    [
                        'product_category_id' => $category->id,
                        'speed' => $prod['speed'] ?? '',
                        'description' => $prod['description'] ?? ($prod['path'] ?? ''),
                        'price' => $prod['price'] ?? null,
                        'show_price' => 1,
                        'web_image' => $prod['path'] ?? null,
                        'path_apps' => $prod['path_apps'] ?? null,
                    ]
                );
            }
        }

        $this->info('Produk & kategori tersimpan.');
    }

    protected function syncProductBenefits(HospitalityApiService $api): void
    {
        $payload = $api->getProductBenefits();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('product_benefits', $payload);

        foreach ($data as $item) {
            $categoryId = null;
            if (isset($item['category_id'])) {
                $categoryId = ProductCategory::where('id', $item['category_id'])
                    ->orWhere('slug', $item['category_id'])
                    ->value('id');
            }

            ProductBenefit::updateOrCreate(
                [
                    'product_category_id' => $categoryId,
                    'description' => $item['description'] ?? ($item['name'] ?? ''),
                ],
                [
                    'icon' => $item['icon'] ?? '',
                ]
            );
        }

        $this->info('Product benefits tersimpan.');
    }

    protected function syncBanners(HospitalityApiService $api): void
    {
        $payload = $api->getBanners();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('banners', $payload);

        foreach ($data as $item) {
            Banner::updateOrCreate(
                ['path' => $item['image'] ?? ($item['path'] ?? null)],
                [
                    'name' => $item['title'] ?? ($item['name'] ?? 'Banner'),
                    'path_apps' => $item['path_apps'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        $this->info('Banners tersimpan.');
    }

    protected function syncCareers(HospitalityApiService $api): void
    {
        $payload = $api->getCareers();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('careers', $payload);

        foreach ($data as $item) {
            // Amankan type ENUM
            $typeCode = Arr::get($item, 'type.code');
            $allowedTypes = ['fulltime', 'contract', 'internship'];
            $type = in_array($typeCode, $allowedTypes, true) ? $typeCode : 'fulltime';

            // Education
            $education = Arr::get($item, 'education_level.name')
                ?? Arr::get($item, 'education_level.code')
                ?? null;

            // Job desc & job reqs (array → text)
            $jobDescArray = Arr::get($item, 'job_description', []);
            $jobReqArray  = Arr::get($item, 'job_requirements', []);

            $jobDesc = is_array($jobDescArray)
                ? implode(PHP_EOL, $jobDescArray)
                : (string) $jobDescArray;

            $jobReqs = is_array($jobReqArray)
                ? implode(PHP_EOL, $jobReqArray)
                : (string) $jobReqArray;

            Career::updateOrCreate(
                [
                    'slug' => $item['slug'] ?? Str::slug($item['title'] ?? Str::random(6)),
                ],
                [
                    'title'            => $item['title'] ?? 'Career',
                    'type'             => $type,
                    'education_level'  => $education,
                    'location'         => $item['location'] ?? null,
                    'description'      => $item['description'] ?? null,
                    'job_description'  => $jobDesc,
                    'job_requirements' => $jobReqs,
                    'image_path'       => $item['image_url']
                        ?? ($item['image'] ?? ($item['image_path'] ?? null)),
                    'is_active'        => Arr::get($item, 'meta.is_active', true) ? 1 : 0,
                    'published_at'     => Arr::get($item, 'meta.published_at'),
                ]
            );
        }

        $this->info('Careers tersimpan.');
    }

    protected function syncProvinces(HospitalityApiService $api): void
{
        $payload = $api->getProvinces();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('provinces', $payload);

        foreach ($data as $item) {
            // Kita pakai name sebagai key unik, tanpa external_id
            Province::updateOrCreate(
                [
                    'name' => $item['name'] ?? null,
                ],
                [
                    'name' => $item['name'] ?? null,
                ]
            );
        }

        $this->info('Provinces tersimpan.');
    }

    protected function syncCities(HospitalityApiService $api): void
    {
        $payload = $api->getCityDistricts();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('cities', $payload);

        foreach ($data as $item) {
            CityDistrict::updateOrCreate(
                ['external_id' => $item['id'] ?? null],
                [
                    'province_id' => $item['province_id'] ?? null,
                    'name' => $item['name'] ?? null,
                    'payload' => $item,
                ]
            );
        }

        $this->info('Cities/districts tersimpan.');
    }

    protected function syncSubdistricts(HospitalityApiService $api): void
    {
        $payload = $api->getSubdistricts();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('subdistricts', $payload);

        foreach ($data as $item) {
            Subdistrict::updateOrCreate(
                ['external_id' => $item['id'] ?? null],
                [
                    'city_id' => $item['city_id'] ?? null,
                    'name' => $item['name'] ?? null,
                    'payload' => $item,
                ]
            );
        }

        $this->info('Subdistricts tersimpan.');
    }

    protected function syncVillages(HospitalityApiService $api): void
    {
        $payload = $api->getVillages();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('villages', $payload);

        foreach ($data as $item) {
            Village::updateOrCreate(
                ['external_id' => $item['id'] ?? null],
                [
                    'subdistrict_id' => $item['subdistrict_id'] ?? null,
                    'name' => $item['name'] ?? null,
                    'payload' => $item,
                ]
            );
        }

        $this->info('Villages tersimpan.');
    }

    protected function syncCompanyDesc(HospitalityApiService $api): void
    {
        $payload = $api->getCompanyDesc();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('company_desc', $payload);

        foreach ((array) $data as $item) {
            CompanyDescription::updateOrCreate(
                ['slug' => $item['slug'] ?? Str::slug($item['title'] ?? Str::random(6))],
                [
                    'title' => $item['title'] ?? 'Company',
                    'content' => $item['content'] ?? ($item['description'] ?? null),
                    'payload' => $item,
                ]
            );
        }

        $this->info('Company description tersimpan.');
    }

    protected function syncAboutUs(HospitalityApiService $api): void
    {
        $payload = $api->getAboutUs();
        $data = Arr::get($payload, 'data', []);
        $this->maybeStoreRaw('about_us', $payload);

        foreach ((array) $data as $item) {
            AboutUs::updateOrCreate(
                ['slug' => $item['slug'] ?? Str::slug($item['title'] ?? Str::random(6))],
                [
                    'title' => $item['title'] ?? 'About',
                    'content' => $item['content'] ?? ($item['description'] ?? null),
                    'payload' => $item,
                ]
            );
        }

        $this->info('About us tersimpan.');
    }

    protected function maybeStoreRaw(string $name, array $payload): void
    {
        if (!$this->option('store-json')) {
            return;
        }

        $dir = 'hospitality';
        Storage::makeDirectory($dir);
        Storage::put("{$dir}/{$name}.json", json_encode($payload, JSON_PRETTY_PRINT));
    }
}
