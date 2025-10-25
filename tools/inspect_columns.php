<?php
// Small helper to inspect DB table columns using Laravel's Schema facade
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$table = $argv[1] ?? 'sudirman_tower_addresses';
try {
    $cols = Schema::getColumnListing($table);
    echo "Columns for table: $table\n";
    foreach ($cols as $c) {
        echo "- $c\n";
    }
    if (empty($cols)) {
        echo "(no columns returned)\n";
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
