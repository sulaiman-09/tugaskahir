<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SudirmanTowerAddress;

try {
    $a = SudirmanTowerAddress::create([
        'tower' => 'T1',
        'floor' => '01',
        'unit' => 'A1',
        'full_address' => 'T1-01-A1',
        'is_active' => 1,
    ]);
    echo "Created: ID={$a->id}\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
