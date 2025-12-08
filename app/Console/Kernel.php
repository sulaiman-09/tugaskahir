<?php

namespace App\Console;

use App\Console\Commands\SyncHospitalityData;
use App\Jobs\SyncHospitalityDataJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        SyncHospitalityData::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Jalankan sync otomatis setiap hari jam 02:00
        $schedule->job(new SyncHospitalityDataJob())->dailyAt('02:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
