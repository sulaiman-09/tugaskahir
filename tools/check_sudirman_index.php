<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    if (Illuminate\Support\Facades\Schema::hasColumn('sudirman_parks', 'visible')) {
        $count = App\Models\SudirmanPark::where('visible', true)->count();
    } else {
        $count = App\Models\SudirmanPark::count();
    }
    echo "OK COUNT={$count}\n";
} catch (Throwable $e) {
    echo "ERR: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
