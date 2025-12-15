<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MigrateLegacyAssets extends Command
{
    protected $signature = 'assets:migrate-legacy';
    protected $description = 'Pindahkan file legacy di public/ ke storage/app/public dan update path di database';

    public function handle(): int
    {
        $this->info('Mulai migrasi assets legacy...');

        $this->migrateNewsImages();

        $this->info('Selesai.');
        return Command::SUCCESS;
    }

    protected function migrateNewsImages(): void
    {
        // news_image & news_image_app yang masih pakai prefix uploads/news/
        $news = DB::table('news')
            ->where(function ($q) {
                $q->where('news_image', 'like', 'uploads/news/%')
                    ->orWhere('news_image_app', 'like', 'uploads/news/%');
            })
            ->get();

        foreach ($news as $row) {
            if ($row->news_image && str_starts_with($row->news_image, 'uploads/news/')) {
                $this->moveFileAndUpdate('news', $row->news_image, 'news_image', $row->id);
            }
            if ($row->news_image_app && str_starts_with($row->news_image_app, 'uploads/news/')) {
                $this->moveFileAndUpdate('news', $row->news_image_app, 'news_image_app', $row->id);
            }
        }
    }

    protected function moveFileAndUpdate(string $targetFolder, string $legacyPath, string $column, int $id): void
    {
        $publicPath = public_path($legacyPath);
        if (!file_exists($publicPath)) {
            $this->warn("File tidak ditemukan: {$legacyPath}");
            return;
        }

        $filename = basename($legacyPath);
        $newPath = "{$targetFolder}/{$filename}";

        // Simpan ke storage/app/public
        $stored = Storage::disk('public')->put($newPath, file_get_contents($publicPath));
        if (!$stored) {
            $this->error("Gagal menyimpan ke storage: {$newPath}");
            return;
        }

        // Update DB
        DB::table('news')->where('id', $id)->update([$column => $newPath]);
        $this->info("Migrasi {$legacyPath} -> {$newPath} (id: {$id}, kolom: {$column})");
    }
}
